<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\Model\Student;
use App\Model\StudentAnswer;
use App\Model\Quiz;
use App\Model\Question;
use App\Model\Ranking;
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

            $data['rank'] = Ranking::where('student_id', $this_student->id)
                ->where('quiz_id', $quiz->id)->first();

            // todo: use semester and year filter too
            $data['total_students'] = Student::where('unit_id', $quiz->unit_id)->count();

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

        // all students later to add semester and year filter
        $all_students = Student::where('unit_id', $quiz->unit_id)->get();
        $ranking = [];
        foreach ($all_students as $student) {
            $student_answers = StudentAnswer::where('quiz_id', $quiz->id)
                ->where('student_id', $student->id)
                ->get();

            if ($student_answers->count() > 0) {

                $correct_count = 0;
                foreach ($student_answers as $answer) {
                    $question = Question::find($answer->question_id);
                    if ($answer->answer == $question->correct_answer)
                        $correct_count++;
                }

                // calculate score in 100%
                $score = ($correct_count * 100) / count($student_answers);

                $ranker = [];
                $ranker['student_id'] = $student->id;
                $ranker['quiz_id'] = $quiz->id;
                $ranker['score'] = $score;
                $ranker['rank_no'] = 0;

                array_push($ranking, $ranker);

            }
        }

        // sort the score in descending - big first --> smaller
        $score = [];
        foreach ($ranking as $key => $row) {
            $score[$key] = $row['score'];
        }
        array_multisort($score, SORT_DESC, $ranking);

        // increment the rank no if already attempt the quiz
        $current_rank = 0;
        foreach ($ranking as $ranker) {
            if ($ranker['score'] > 0 && $ranker['quiz_id'] == $quiz->id) {
                $current_rank++;
                $ranker['rank_no'] = $current_rank;

                Ranking::create([
                    'student_id' => $ranker['student_id'],
                    'quiz_id' => $ranker['quiz_id'],
                    'rank_no' => $ranker['rank_no'],
                    'score' => $ranker['score'],
                ]);
            }
        }
    }

    public function submit_group_answers(Request $request, $quiz_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $quiz = Quiz::find($quiz_id);
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $quiz->unit_id)
            ->first();

        $this_team = Student::->with('unit', 'user')
            ->where('unit_id', $quiz->unit_id)
            ->where('team_number', $this_student->team_number)
            ->get();

        $input = $request->only(['answers']);
        $answers = json_decode($input['answers'], true);

        // save answers for all students
        if (isset($answers)) {
            foreach ($answers as $answer) {
                foreach ($this_team as $team_member) {
                    StudentAnswer::create([
                        'student_id' => $team_member->id,
                        'question_id' => $answer['question_id'],
                        'quiz_id' => $quiz_id,
                        'answer' => $answer['answer'],
                    ]);
                }
            }
        }

        // all students later to add semester and year filter
        $all_students = Student::where('unit_id', $quiz->unit_id)->get();

        // find how many total groups in a unit
        $number_of_groups = 0;
        foreach ($all_students as $student) {
            if (isset($student->team_number)) {
                if ($student->team_number > $number_of_groups) {
                    $number_of_groups = $student->team_number;
                }
            }
        }

        // add to rank if attempted quiz
        $ranking = [];
        foreach ($all_students as $student) {
            $student_answers = StudentAnswer::where('quiz_id', $quiz->id)
                ->where('student_id', $student->id)
                ->orderBy('team_no');
                ->get();

            if ($student_answers->count() > 0) {

                $correct_count = 0;
                foreach ($student_answers as $answer) {
                    $question = Question::find($answer->question_id);
                    if ($answer->answer == $question->correct_answer)
                        $correct_count++;
                }

                // calculate score in 100%
                $score = ($correct_count * 100) / count($student_answers);

                $ranker = [];
                $ranker['student_id'] = $student->id;
                $ranker['quiz_id'] = $quiz->id;
                $ranker['score'] = $score;
                $ranker['rank_no'] = 0;

                if (isset($student->team_number)) {
                    $ranker['team_no'] = $student->team_number
                } else {
                    $ranker['team_no'] = 0;
                }

                array_push($ranking, $ranker);

            }
        }

        // sort the score in descending - big first --> smaller
        $score = [];
        foreach ($ranking as $key => $row) {
            $score[$key] = $row['score'];
        }
        array_multisort($score, SORT_DESC, $ranking);

        // increment the rank no if already attempt the quiz
        $current_rank = 0;
        $checked_team = [];
        for ($i=0; $i < $number_of_groups; $i++) {
            foreach ($ranking as $ranker) {
                // if found the team
                if ($ranker['team_no'] == $i) {
                    // if found the right quiz is attempted
                    if ($ranker['score'] > 0 && $ranker['quiz_id'] == $quiz->id) {
                        // check if the team is already checked before incrementing rank
                        $checked_team['team_no'] = $ranker['team_no'];
                        $checked_team['rank_no'] = $current_rank;

                        if ($ranker['team_no'] != $checked_team['team_no']) {
                            $current_rank++;
                        }

                        // get team members based on checked team no
                        $student_team = Student::where('unit_id', $quiz->unit_id)
                            ->where('team_number', $ranker['team_no'])->get();

                        // create rank for the team members only
                        foreach ($student_team as $team_member) {
                            Ranking::create([
                                'student_id' => $team_member->id,
                                'quiz_id' => $ranker['quiz_id'],
                                'rank_no' => $checked_team['rank_no'],
                                'score' => $ranker['score'],
                            ]);
                        }
                    }
                }
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
