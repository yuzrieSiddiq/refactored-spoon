<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Unit;
use App\Model\Quiz;
use App\Model\Ranking;
use App\Model\Question;
use App\Model\Student;
use App\Model\StudentAnswer;

class ReportingController extends Controller
{
    /**
     * GET unit report
     * return view()
     */
    public function unit_report($unit_id)
    {
        $data = [];
        $data['unit'] = Unit::find($unit_id);

        return view('report.unit', $data);
    }

    /**
     * GET quiz report + ranking
     * return view()
     */
    public function quiz_report($quiz_id)
    {
        $data = [];

        $quiz = Quiz::find($quiz_id);
        $questions = Question::where('quiz_id', $quiz_id)->get();
        $students = Student::with(['user' => function($query) {
                $query->with('student_info')->get();
            }])
            ->where('unit_id', $quiz->unit_id)
            ->where('year', $quiz->year)
            ->where('semester', $quiz->semester)
            ->get();
        $rankings = Ranking::with(['student' => function($query) {
                $query->with(['user' => function($next_query) {
                    $next_query->with('student_info')->get();
                }])->get();
            }])
            ->where('quiz_id', $quiz_id)
            ->orderBy('rank_no')->get();

        // return response()->json($rankings);
        $attempts = [];

        $attempted_count = 0;
        $unattempted_count = 0;
        $pass_count = 0;
        $fail_count = 0;

        foreach ($students as $student) {
            $this_data = [];
            $this_data['student_id'] = $student->id;
            $this_data['student_std_id'] = $student->user->student_info->student_id;
            $this_data['student_name'] = $student->user->firstname . " " . $student->user->lastname;
            $this_data['attempted'] = false;
            $this_data['pass'] = null;
            $this_data['correct'] = 0;
            $this_data['wrong'] = 0;

            // check if student has already attempted the quiz
            $student_answers = StudentAnswer::where('student_id', $student->id)
                ->where('quiz_id', $quiz->id)->get();
            if ($student_answers->count() > 0) {
                $this_data['attempted'] = true;
                $attempted_count++;

                // if already attempted, find how many correct, wrong and pass status
                foreach ($student_answers as $answer) {
                    $correct_answer = Question::find($answer->question_id)->correct_answer;
                    if ($answer->answer == $correct_answer) {
                        $this_data['correct']++;
                    } else {
                        $this_data['wrong']++;
                    }
                }
            } else {
                $unattempted_count++;
            }

            if ($this_data['attempted']) {
                if ($this_data['correct'] >= $student_answers->count() / 2) {
                    $this_data['pass'] = true;
                    $pass_count++;
                } else {
                    $this_data['pass'] = false;
                    $fail_count++;
                }
            }

            array_push($attempts, $this_data);
        }

        // group part starts here
        $group_quiz = Quiz::where('title', $quiz->title)
            ->where('semester', $quiz->semester)->where('year', $quiz->year)
            ->where('type', 'group')->first();
        $group_rankings = Ranking::with(['student' => function($query) {
                $query->with(['user' => function($next_query) {
                    $next_query->with('student_info')->get();
                }])->get();
            }])
            ->where('quiz_id', $group_quiz->id)
            ->orderBy('rank_no')->get();


        $data['quiz'] = $quiz;
        $data['students'] = $students;
        $data['attempts'] = $attempts;
        $data['rankings'] = $rankings;

        $data['group_quiz'] = $group_quiz;
        $data['group_rankings'] = $group_rankings;

        $data['attempted_count'] = $attempted_count;
        $data['unattempted_count'] = $unattempted_count;
        $data['pass_count'] = $pass_count;
        $data['fail_count'] = $fail_count;

        // return response()->json($data);
        return view('report.quiz', $data);
    }

    /**
     * POST student report
     * return response()->json()
     */
    public function student_report($student_id, $quiz_id)
    {
        $student = Student::with(['user' => function($query) {
            $query->with('student_info')->get();
        }])->find($student_id);
        $quiz = Quiz::find($quiz_id);
        $questions = Question::where('quiz_id', $quiz_id)->get();
        $ranking = Ranking::where('quiz_id', $quiz_id)->where('student_id', $student_id)->first();
        $last_rank = Ranking::where('quiz_id', $quiz_id)->orderBy('rank_no', 'desc')->first();

        $data = [];
        $data['individual'] = [];

        $data['individual']['student_id'] = $student->id;
        $data['individual']['student_std_id'] = $student->user->student_info->student_id;
        $data['individual']['student_name'] = $student->user->firstname . " " . $student->user->lastname;
        $data['individual']['attempted'] = false;
        $data['individual']['pass'] = null;
        $data['individual']['correct'] = 0;
        $data['individual']['wrong'] = 0;
        $data['individual']['wrong_questions'] = [];
        $data['individual']['total_questions'] = $questions->count();
        $data['individual']['last_rank'] = $last_rank->rank_no;
        $data['individual']['rank'] = $ranking->rank_no;
        $data['individual']['score'] = $ranking->score;
        $data['individual']['remaining_score'] = 100 - $ranking->score;

        // check if student has already attempted the quiz
        $student_answers = StudentAnswer::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)->get();
        if ($student_answers->count() > 0) {
            $data['individual']['attempted'] = true;

            // if already attempted, find how many correct, wrong and pass status
            foreach ($student_answers as $answer) {
                $correct_answer = Question::find($answer->question_id)->correct_answer;
                $question = Question::find($answer->question_id)->question;
                if ($answer->answer == $correct_answer) {
                    $data['individual']['correct']++;
                } else {
                    $data['individual']['wrong']++;
                    array_push($data['individual']['wrong_questions'], $question);
                }
            }

            if ($data['individual']['attempted']) {
                if ($data['individual']['correct'] > $data['individual']['total_questions']/2) {
                    $data['individual']['pass'] = true;
                } else {
                    $data['individual']['pass'] = false;
                }
            }
        }

        // group part starts here
        $student_group = $student->team_number;
        $group_leader = Student::with(['user' => function($query) {
            $query->with('student_info')->get();
        }])->where('team_number', $student_group)
            ->where('is_group_leader', true)->first();

        $group_quiz = Quiz::where('title', $quiz->title)
            ->where('semester', $quiz->semester)->where('year', $quiz->year)
            ->where('type', 'group')->first();
        $group_questions = Question::where('quiz_id', $group_quiz->id)->get();
        $group_ranking = Ranking::where('quiz_id', $group_quiz->id)->where('student_id', $student_id)->first();
        $group_last_rank = Ranking::where('quiz_id', $group_quiz->id)->orderBy('rank_no', 'desc')->first();

        $data['group'] = [];
        $data['group']['student_id'] = $group_leader->id;
        $data['group']['student_std_id'] = $group_leader->user->student_info->student_id;
        $data['group']['student_name'] = $group_leader->user->firstname . " " . $group_leader->user->lastname;
        $data['group']['attempted'] = false;
        $data['group']['pass'] = null;
        $data['group']['correct'] = 0;
        $data['group']['wrong'] = 0;
        $data['group']['wrong_questions'] = [];
        $data['group']['total_questions'] = $group_questions->count();
        $data['group']['last_rank'] = $group_last_rank->rank_no;
        $data['group']['rank'] = $group_ranking->rank_no;
        $data['group']['score'] = $group_ranking->score;
        $data['group']['remaining_score'] = 100 - $group_ranking->score;
        $data['group']['group_no'] = $group_leader->team_number;

        // check if student has already attempted the quiz
        $student_answers = StudentAnswer::where('student_id', $group_leader->id)
            ->where('quiz_id', $group_quiz->id)->get();
        if ($student_answers->count() > 0) {
            $data['group']['attempted'] = true;

            // if already attempted, find how many correct, wrong and pass status
            foreach ($student_answers as $answer) {
                $correct_answer = Question::find($answer->question_id)->correct_answer;
                $question = Question::find($answer->question_id)->question;
                if ($answer->answer == $correct_answer) {
                    $data['group']['correct']++;
                } else {
                    $data['group']['wrong']++;
                    array_push($data['group']['wrong_questions'], $question);
                }
            }
        }

        return response()->json($data);
    }
}
