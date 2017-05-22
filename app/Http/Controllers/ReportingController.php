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

        $data['quiz'] = $quiz;
        $data['students'] = $students;
        $data['attempts'] = $attempts;
        $data['rankings'] = $rankings;

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

        $data = [];
        $data['student_id'] = $student->id;
        $data['student_std_id'] = $student->user->student_info->student_id;
        $data['attempted'] = false;
        $data['pass'] = null;
        $data['correct'] = 0;
        $data['wrong'] = 0;
        $data['total_questions'] = $questions->count();

        // check if student has already attempted the quiz
        $student_answers = StudentAnswer::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)->get();
        if ($student_answers->count() > 0) {
            $data['attempted'] = true;

            // if already attempted, find how many correct, wrong and pass status
            foreach ($student_answers as $answer) {
                $correct_answer = Question::find($answer->question_id)->correct_answer;
                if ($answer->answer == $correct_answer) {
                    $data['correct']++;
                } else {
                    $data['wrong']++;
                }
            }
        }

        return response()->json($data);
    }
}
