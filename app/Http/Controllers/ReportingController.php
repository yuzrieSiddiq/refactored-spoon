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
     * NOTE: uses of group_quiz and individual_quiz are interchangeable most of the time
     * return view()
     */
    public function quiz_report($quiz_id)
    {
        $data = [];

        // get the quizzes and its details
        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();
        $questions = Question::where('quiz_id', $quiz_id)->get();

        // get all students under this unit
        $students = Student::with(['user' => function($query) {
                $query->with('student_info')->get();
            }])
            ->where('unit_id', $quiz_group->unit_id)
            ->where('year', $quiz_group->year)
            ->where('semester', $quiz_group->semester)
            ->get();

        // get all rankings for this individual quiz
        $rankings = Ranking::with(['student' => function($query) {
                $query->with(['user' => function($next_query) {
                    $next_query->with('student_info')->get();
                }])->get();
            }])
            ->where('quiz_id', $quiz_individual->id)
            ->orderBy('rank_no')->get();

        /**
         * individual quiz implementation
         * */
        $attempts = [];

        $page_count = 0;
        $pass_count = 0;
        $fail_count = 0;
        $attempted_count = 0;
        $unattempted_count = 0;

        /**
         * For each student, get their attempts
         * page_count is used to manually paginate the pages
         * page_count indicates, at which page is this data at
         */
        foreach ($students as $count => $student) {
            $this_data = [];

            // add to page based on the number of students
            $this_data['count'] = $count;
            if ($this_data['count'] % 5 == 0) {
                $page_count++;
            }
            $this_data['page_count'] = $page_count;

            // add student details and his attempt details
            $this_data['student_id'] = $student->id;
            $this_data['student_std_id'] = $student->user->student_info->student_id;
            $this_data['student_name'] = $student->user->firstname . " " . $student->user->lastname;
            $this_data['attempted'] = false;
            $this_data['pass'] = null;
            $this_data['correct'] = 0;
            $this_data['wrong'] = 0;

            // check if student has already attempted the quiz
            $student_answers = StudentAnswer::where('student_id', $student->id)
                ->where('quiz_id', $quiz_individual->id)->get();
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

        /**
         * group quiz implementation
         * */
        $group_leaders = Student::with(['user' => function($query) {
                $query->with('student_info')->get();
            }])
            ->where('unit_id', $quiz_group->unit_id)
            ->where('year', $quiz_group->year)
            ->where('semester', $quiz_group->semester)
            ->where('is_group_leader', true)
            ->get();

        $group_rankings = Ranking::with(['student' => function($query) {
                $query->with(['user' => function($next_query) {
                    $next_query->with('student_info')->get();
                }])->get();
            }])
            ->where('quiz_id', $quiz_group->id)
            ->orderBy('rank_no')->get();

        $group_attempted_count = 0;
        $group_unattempted_count = 0;
        $group_pass_count = 0;
        $group_fail_count = 0;

        $group_attempts = [];
        foreach ($group_leaders as $student) {
            $this_data = [];
            $this_data['team_number'] = $student->team_number;
            $this_data['attempted'] = false;
            $this_data['pass'] = null;
            $this_data['correct'] = 0;
            $this_data['wrong'] = 0;

            // check if student has already attempted the quiz
            $student_answers = StudentAnswer::where('student_id', $student->id)
                ->where('quiz_id', $quiz_group->id)->get();
            if ($student_answers->count() > 0) {
                $this_data['attempted'] = true;
                $group_attempted_count++;

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
                $group_unattempted_count++;
            }

            if ($this_data['attempted']) {
                if ($this_data['correct'] >= $student_answers->count() / 2) {
                    $this_data['pass'] = true;
                    $group_pass_count++;
                } else {
                    $this_data['pass'] = false;
                    $group_fail_count++;
                }
            }

            array_push($group_attempts, $this_data);
        }

        $data['page_count'] = $page_count;

        $data['quiz'] = $quiz_individual;
        $data['students'] = $students;
        $data['attempts'] = $attempts;
        $data['rankings'] = $rankings;

        $data['group_quiz'] = $quiz_group;
        $data['group_rankings'] = $group_rankings;

        $data['attempted_count'] = $attempted_count;
        $data['unattempted_count'] = $unattempted_count;
        $data['pass_count'] = $pass_count;
        $data['fail_count'] = $fail_count;

        $data['group_attempted_count'] = $group_attempted_count;
        $data['group_unattempted_count'] = $group_unattempted_count;
        $data['group_pass_count'] = $group_pass_count;
        $data['group_fail_count'] = $group_fail_count;

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

        // if attempted, add the remaining info
        if (isset($data['individual']['attempted']) && $data['individual']['attempted'] == true) {
            $data['individual']['last_rank'] = $last_rank->rank_no;
            $data['individual']['rank'] = $ranking->rank_no;
            $data['individual']['score'] = $ranking->score;
            $data['individual']['remaining_score'] = 100 - $ranking->score;
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

        // if no group
        if (!isset($group_leader)) {
            $data['group'] = null;
        } else {
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

            // if already attempted, add the remaining info
            if (isset($data['group']['attempted']) && $data['group']['attempted'] == true) {
                $data['group']['last_rank'] = $group_last_rank->rank_no;
                $data['group']['rank'] = $group_ranking->rank_no;
                $data['group']['score'] = $group_ranking->score;
                $data['group']['remaining_score'] = 100 - $group_ranking->score;
                $data['group']['group_no'] = $group_leader->team_number;
            }
        }

        return response()->json($data);
    }
}
