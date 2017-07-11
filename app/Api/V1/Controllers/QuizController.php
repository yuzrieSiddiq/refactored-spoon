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
use App\Model\Settings;
use Dingo\Api\Routing\Helpers;

class QuizController extends Controller
{
    use Helpers;

    public function index($unit_id)
    {
        // settings semester and year
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->first();

        $quizzes = Quiz::where('unit_id', $unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->where('status', 'open')
            ->get();

        // all students
        $all_students = Student::where('unit_id', $unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->whereNotNull('team_number')
            ->orderBy('team_number', 'asc')->get();

        // find how many total groups in a unit
        $number_of_groups = 0;
        foreach ($all_students as $student) {
            if (isset($student->team_number)) {
                if ($student->team_number > $number_of_groups) {
                    $number_of_groups = $student->team_number;
                }
            }
        }

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

            $data['total_students'] = Student::where('unit_id', $quiz->unit_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->count();
            $data['total_teams'] = $number_of_groups;
            $data['this_student'] = $this_student;

            array_push($quizzes_data, $data);
        }


        return response()->json($quizzes_data);
    }

    public function show($quiz_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $quiz = Quiz::find($quiz_id);

        // settings semester and year
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $quiz->unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->first();

        $all_questions = Question::where('quiz_id', $quiz_id)->get();
        $questions = Self::select_random_question($quiz->show_questions, $all_questions);

        return response()->json($questions);
    }

    public function select_random_question($allowed_questions, $questions)
    {
        // non repeating numbers between 1 - $allowed_questions
        $choices = range( 0, count($questions)-1 );
        shuffle($choices);
        $selected = array_slice($choices, 0, $allowed_questions);
        $selected_questions = [];

        // for each found questions, add to array to be shown to students
        foreach ($selected as $choice) {
            array_push($selected_questions, $questions[$choice]);
        }

        // return the array
        return $selected_questions;
    }

    public function submit_answers(Request $request, $quiz_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $quiz = Quiz::find($quiz_id);

        // settings semester and year
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        if ($quiz->type == "individual") {
            $this_student = Student::with('unit', 'user')
                ->where('user_id', $auth_user->id)
                ->where('unit_id', $quiz->unit_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->first();

            $input = $request->only(['answers']);
            $answers = json_decode($input['answers'], true);

            // set answers for the student
            if (isset($answers)) {
                foreach ($answers as $answer) {
                    StudentAnswer::create([
                        'student_id' => $this_student->id,
                        'question_id' => $answer['question_id'],
                        'quiz_id' => $quiz->id,
                        'answer' => $answer['answer'],
                    ]);
                }
            }

            // get the student answers to calculate the scores
            $student_answers = StudentAnswer::where('quiz_id', $quiz->id)
                ->where('student_id', $this_student->id)
                ->get();

            // get the current ranks
            $student_ranks = Ranking::where('quiz_id', $quiz->id)
                ->whereNotNull('rank_no')
                ->orderBy('rank_no', 'desc')
                ->get();

            // if quiz has been attempted, calculate the score
            if ($student_answers->count() > 0) {
                $correct_count = 0;
                foreach ($student_answers as $answer) {
                    $question = Question::find($answer->question_id);
                    if ($answer->answer == $question->correct_answer)
                        $correct_count++;
                }

                // calculate score in 100%
                $score = ($correct_count * 100) / count($student_answers);

                // add to the last rank existing (not sorted yet)
                if ($student_ranks->count() > 0) {
                    $ranking = Ranking::create([
                        'student_id' => $this_student->id,
                        'quiz_id' => $quiz->id,
                        'score' => $score,
                        'rank_no' => $student_ranks[0]->rank_no + 1
                    ]);
                } else {
                    $ranking = Ranking::create([
                        'student_id' => $this_student->id,
                        'quiz_id' => $quiz->id,
                        'score' => $score,
                        'rank_no' => 1
                    ]);
                }
            }

            // rearrange the rank based on the score (sorted ranks)
            $current_ranks = Ranking::where('quiz_id', $quiz->id)
                ->orderBy('score', 'desc')->get();

            foreach ($current_ranks as $count => $ranks) {
                $ranks->update([
                    'rank_no' => $count+1
                ]);
            }

        } else if ($quiz->type == "group") {

            $this_student = Student::with('unit', 'user')
                ->where('user_id', $auth_user->id)
                ->where('unit_id', $quiz->unit_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->first();

            $this_team = Student::with('unit', 'user')
                ->where('unit_id', $quiz->unit_id)
                ->where('team_number', $this_student->team_number)
                ->where('semester', $semester)
                ->where('year', $year)
                ->get();

            // all students
            $all_students = Student::where('unit_id', $quiz->unit_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->whereNotNull('team_number')
                ->orderBy('team_number', 'asc')->get();

            // find how many total groups in a unit
            $number_of_groups = 0;
            foreach ($all_students as $student) {
                if (isset($student->team_number)) {
                    if ($student->team_number > $number_of_groups) {
                        $number_of_groups = $student->team_number;
                    }
                }
            }

            $input = $request->only(['answers']);
            $answers = json_decode($input['answers'], true);

            // save answers for all students
            // its fine to save the wrong answer - the check is not here - see line 235
            if (isset($answers)) {
                foreach ($answers as $answer) {
                    foreach ($this_team as $team_member) {
                        StudentAnswer::create([
                            'student_id' => $team_member->id,
                            'question_id' => $answer['question_id'],
                            'quiz_id' => $quiz->id,
                            'answer' => $answer['answer'],
                        ]);
                    }
                }
            }

            $student_answers = StudentAnswer::where('quiz_id', $quiz->id)
                ->where('student_id', $this_student->id)
                ->get();

            // get the current ranks
            $student_ranks = Ranking::where('quiz_id', $quiz->id)
                ->whereNotNull('rank_no')
                ->orderBy('rank_no', 'desc')
                ->get();

            // if quiz has been attempted, calculate the score
            // - here is where its important to get the score
            if ($student_answers->count() > 0) {
                $correct_count = 0;
                foreach ($student_answers as $answer) {
                    $question = Question::find($answer->question_id);
                    if ($answer->answer == "4 POINTS")
                        $correct_count += 4;
                    else if ($answer->answer == "2 POINTS")
                        $correct_count += 2;
                    else if ($answer->answer == "1 POINTS")
                            $correct_count += 1;
                    else {
                        $correct_count += 0;
                    }
                }

                // calculate score in 100% (e.g: 36/40 = 90%)
                // maximum marks is 4 POINTS
                $score = ($correct_count * 100) / (count($student_answers)*4);

                // add to the last rank existing (not sorted yet)
                if ($student_ranks->count() > 0) {
                    $ranking = Ranking::create([
                        'student_id' => $this_student->id,
                        'quiz_id' => $quiz->id,
                        'score' => $score,
                        'rank_no' => $student_ranks[0]->rank_no + 1
                        // ^ because the first entry has the lowest rank (biggest number)
                    ]);
                } else {
                    $ranking = Ranking::create([
                        'student_id' => $this_student->id,
                        'quiz_id' => $quiz->id,
                        'score' => $score,
                        'rank_no' => 1
                        // ^ starts at 1 not 0
                    ]);
                }
            }

            // rearrange the rank based on the score (sorted ranks)
            $current_ranks = Ranking::with('student')
                ->where('quiz_id', $quiz->id)
                ->orderBy('score', 'desc')->get();

            // first update the group leaders rank
            $count_ranks = 0;
            foreach ($current_ranks as $ranker) {
                if ($ranker->student->is_group_leader) {
                    $count_ranks++;
                    $ranker->update([
                        'rank_no' => $count_ranks
                    ]);
                }
            }

            foreach ($current_ranks as $current_ranker) {
                if ($current_ranker->student->is_group_leader) {
                    foreach ($all_students as $student) {
                        // if the student is from the same team
                        if ($student->team_number == $current_ranker->student->team_number) {

                            // find if the rank already exist for that student
                            $member_ranking = Ranking::where('student_id', $student->id)
                            ->where('quiz_id', $quiz->id)->first();

                            if (!isset($member_ranking)) {
                                Ranking::create([
                                    'student_id' => $student->id,
                                    'quiz_id' => $quiz->id,
                                    'score' => $current_ranker->score,
                                    'rank_no' => $current_ranker->rank_no
                                ]);
                            } else {
                                $member_ranking->update([
                                    'score' => $current_ranker->score,
                                    'rank_no' => $current_ranker->rank_no
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function quiz_report($quiz_id)
    {
        // settings semester and year
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('semester', $semester)
            ->where('year', $year)
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
