<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\Model\Student;
use App\Model\StudentAnswer;
use App\Model\Quiz;
use App\Model\Question;
use Dingo\Api\Routing\Helpers;

class QuizController extends Controller
{
    use Helpers;

    public function index($unit_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->first();

        $quizzes = Quiz::where('unit_id', $unit_id)
            ->where('semester', $this_student->semester)
            ->where('year', $this_student->year)
            ->where('status', 'open')
            ->get();

        $quizzes_data = [];
        foreach ($quizzes as $quiz) {
            $data = [];
            $data['quiz'] = $quiz;
            $data['has_been_attempted'] = false;
            $data['answers_count'] = StudentAnswer::where('student_id', $this_student->id)
                ->where('quiz_id', $quiz->id)->count();

            $student_answers = StudentAnswer::where('student_id', $this_student->id)
                ->where('quiz_id', $quiz->id)->get();

            $data['correct_count'] = 0;
            foreach ($student_answers as $answer) {
                $correct_answer = Question::find($answer->question_id)->correct_answer;
                if ($answer->answer == $correct_answer) {
                    $data['correct_count']++;
                }
            }

            if ($data['answers_count'] > 0)
                $data['has_been_attempted'] = true;

            array_push($quizzes_data, $data);
        }

        return response()->json($quizzes_data);
    }

    public function show($quiz_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->first();

        $quiz = Quiz::find($quiz_id);
        $questions = Question::where('quiz_id', $quiz_id)->get();

        // $data['this_student'] = $this_student;
        // $data['quiz'] = $quiz;
        // $data['questions'] = $questions;

        return response()->json($questions);
    }

    public function submit_answers(Request $request, $quiz_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $quiz = Quiz::find($quiz_id);
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $quiz->unit_id)
            ->first();

        $input = $request->only(['answers']);
        $answers = json_decode($input['answers'], true);

        // return response()->json($answers);

        if (isset($answers)) {
            foreach ($answers as $answer) {
                StudentAnswer::create([
                    'student_id' => $this_student->id,
                    'question_id' => $answer['question_id'],
                    'quiz_id' => $quiz_id,
                    'answer' => $answer['answer'],
                ]);
            }
        }
    }

    public function quiz_report($quiz_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->first();

        $quiz = Quiz::find($quiz_id);
        $answers = StudentAnswer::where('quiz_id', $quiz_id)
            ->where('student_id', $this_student->id)
            ->get();

        $correct_count = 0;
        $wrong_count = 0;

        if (isset($answers)) {
            foreach ($answers as $answer) {
                $question = Question::find($answer->question_id);

                if ($answer->answer == $question->correct_answer) {
                    $correct_count++;
                } else {
                    $wrong_count++;
                }
            }
        }

        $data['correct_answers'] = $correct_count;
        $data['wrong_answers'] = $wrong_count;
        $data['quiz'] = $quiz;

        return response()->json($data);
    }
}
