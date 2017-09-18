<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Quiz;
use App\Model\Group;
use App\Model\Ranking;
use App\Model\Question;
use App\Model\Settings;
use App\Model\StudentAnswer;
use App\Model\Student;

class ResultsController extends Controller
{
    /**
     * return view
     */
    public function overall_results($quiz_id)
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $data = [];
        $data['quiz'] = $quiz_group;
        $data['quiz_individual'] = $quiz_individual;
        /**
         * 2nd part - compare results between teams (group_leaders)
         * */
        $team_leaders = Student::with(['user' => function($query) { $query->with('student_info')->get(); }])
            ->where('unit_id', $quiz_group->unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->where('is_group_leader', true)
            ->get();

        $team_rankings = Ranking::with(['student' => function($query) {
                $query->with(['user' => function($next_query) {
                    $next_query->with('student_info')->get();
                }])->get();
            }])
            ->where('quiz_id', $quiz_group->id)
            ->orderBy('rank_no')->get();

        $data['team_leaders'] = $team_leaders;
        $data['team_rankings'] = $team_rankings;

        /**
         * 1st part - overall quiz results
         * */
        // get the ranking along with the student details (name, year)
        $rankings = Ranking::where('quiz_id', $quiz_individual->id)
            ->with(['student' => function($query) {
                $semester = Settings::where('name', 'semester')->first()->value;
                $year = Settings::where('name', 'year')->first()->value;

                // get student info (student_id) => $ranking->student->user->student_id
                $query->with(['user' => function($user_query) { $user_query->with('student_info')->get(); }])
                    ->where('year', $year)
                    ->where('semester', $semester)
                    ->get();

        }])->get();

        // add the team ranking information
        foreach ($rankings as $ranking) {
            $team_ranking = Ranking::where('student_id', $ranking->student_id)
                ->where('quiz_id', $quiz_group->id)
                ->first();
            $ranking['t_score'] = isset($team_ranking->score) ? $team_ranking->score : 'NA';
            $ranking['t_rank_no'] = isset($team_ranking->rank_no) ? $team_ranking->rank_no : 'NA';
        }
        $data['rankings'] = $rankings;

        /**
         * 3rd part - compare results by questions
         * */
        $questions = Question::where('quiz_id', $quiz_group->id)->get();
        $individual_quiz_answers = StudentAnswer::with([
            'question' => function($query) use ($quiz_individual) {
                $query->where('quiz_id', $quiz_individual->id)->get();
            },
            'student' => function($query) use ($year, $semester) {
                $query->where('year', $year)->where('semester', $semester)->get();
            }
        ])->get();

        $group_quiz_answers = StudentAnswer::with([
            'question' => function($query) use ($quiz_group) {
                $query->where('quiz_id', $quiz_group->id)->get();
            },
            'student' => function($query) use ($year, $semester) {
                $query->where('year', $year)->where('semester', $semester)->get();
        }])->get();

        $data['questions'] = $questions;
        $data['group_quiz_answers'] = $group_quiz_answers;
        $data['individual_quiz_answers'] = $individual_quiz_answers;
        /**
         * 4th part - compare results between students in different groups
         * */
        $individual_quiz_groups = Group::where('quiz_id', $quiz_individual->id)
            ->with(['quiz' => function ($query) {
                $query->with(['ranking'])->get();
            }])->get();
        $group_quiz_groups = Group::where('quiz_id', $quiz_group->id)
            ->with(['quiz' => function ($query) {
                $query->with(['ranking'])->get();
            }])->get();

        $data['individual_quiz_groups'] = $individual_quiz_groups;
        $data['group_quiz_groups'] = $group_quiz_groups;

        // return response()->json($data);
        return view ('quiz.results', $data);
    }

    /**
     * AJAX CALL - POST
     * get student answer
     */
    public function get_student_answers($quiz_id, $student_id)
    {
        $data = [];
        $data['answers'] = StudentAnswer::with('question')
            ->where('quiz_id', $quiz_id)
            ->where('student_id', $student_id)
            ->get();

        return response()->json($data);
    }

    /**
     * AJAX CALL - POST
     */
    public function group_results(Request $request, $quiz_id, $group_id)
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $group= Group::find($group_id);
        $questions = Question::where('quiz_id', $quiz_group->id)->get();

        // get answers from individual quiz
        $answer_list = StudentAnswer::with('question')
            ->where('quiz_id', $quiz_individual->id)
            ->where('student_id', $request['student_id'])
            ->get();

        // get answers from team quiz
        foreach ($answer_list as $answer) {
            $team_answer = StudentAnswer::where('quiz_id', $quiz_group->id)
                ->where('student_id', $request['student_id'])
                ->get();

            // append the answer if exist
            if (!empty($team_answer)) {
                foreach ($team_answer as $t_answer) {
                    if ($t_answer->question_id == $answer->question_id) {
                        $answer['team_answer'] = $t_answer->answer;
                        break;
                    }
                }
            }
        }

        return response()->json($answer_list);
    }
}
