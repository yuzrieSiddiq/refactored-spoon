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
        // add the team ranking information into overall results table
        foreach ($rankings as $ranking) {
            $team_ranking = Ranking::where('student_id', $ranking->student_id)
                ->where('quiz_id', $quiz_group->id)
                ->first();
            $ranking['t_score'] = isset($team_ranking->score) ? $team_ranking->score : 'NA';
            $ranking['t_rank_no'] = isset($team_ranking->rank_no) ? $team_ranking->rank_no : 'NA';
        }
        $data['rankings'] = $rankings;

        /**
         * 2nd part - compare results between teams (group_leaders)
         * */
        $team_leaders = Student::with(['user' => function($query) { $query->with('student_info')->get(); }])
            ->where('unit_id', $quiz_group->unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->where('is_group_leader', true)
            ->get();

        foreach ($team_leaders as $leader) {
            $ranking = Ranking::where('student_id', $leader->id)
                ->where('quiz_id', $quiz_group->id)
                ->first();

            // get color to put for the charts
            $leader['color'] = implode(',', $this->generate_rgb($leader->id));
            $leader['t_rank'] = isset($ranking) ? $ranking->rank_no: 0;
            $leader['t_score'] = isset($ranking) ? $ranking->score : 0;
        }
        $data['team_leaders'] = $team_leaders;

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
        $groups = []; // to store all the students following their group
        $student_groups = Student::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $semester)->where('year', $year)
            ->orderBy('group_number', 'asc')->groupBy('group_number')->get();

        foreach ($student_groups as $student) {
            // part 1 - individual quiz
            $ranking = [];
            $i_total_count = $i_passed_count = 0;
            $t_total_count = $t_passed_count = 0;

            // get the students in this group... later append his ranking if exist
            $i_students = Student::where('year', $year)->where('semester', $semester)->where('group_number', $student->group_number)->get();
            foreach ($i_students as $student) {
                $student['ranking'] = Ranking::where('student_id', $student->id)->where('quiz_id', $quiz_individual->id)->first();
                if (!is_null($student['ranking'])) {
                    $i_passed_count = $student['ranking']->score >= 50 ? $i_passed_count+1 : $i_passed_count;
                    $i_total_count++;
                }
            }
            $ranking['i_total_count'] = $i_total_count;
            $ranking['i_passed_count'] = $i_passed_count;
            $ranking['i_passed_count_percentage'] = $i_total_count != 0 ? $i_passed_count*100 / $i_total_count : 0;

            // part  2 - team quiz
            $t_students = Student::where('unit_id', $quiz_group->unit_id)
                ->where('semester', $semester)->where('year', $year)
                ->where('group_number', $student->group_number)->where('is_group_leader', true)->get();
            foreach ($t_students as $student) {
                $student['ranking'] = Ranking::where('student_id', $student->id)->where('quiz_id', $quiz_group->id)->first();
                if (!is_null($student['ranking'])) {
                    $t_passed_count = $student['ranking']->score >= 50 ? $t_passed_count+1 : $t_passed_count;
                    $t_total_count++;
                }
            }
            $ranking['t_total_count'] = $t_total_count;
            $ranking['t_passed_count'] = $t_passed_count;
            $ranking['t_passed_count_percentage'] = $t_total_count != 0 ? $t_passed_count*100 / $t_total_count : 0;
            $ranking['group_number'] = $student->group_number;
            $ranking['group_rgb'] = implode(',', $this->generate_rgb($student->id));
            // ^ need only one because the graph shows only for passed
            array_push($groups, $ranking);
        }

        $data['groups'] = $groups;

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

    public function generate_rgb($num) {
        $hash = md5('color' . $num); // modify 'color' to get a different palette
        return array(
            hexdec(substr($hash, 0, 2)), // r
            hexdec(substr($hash, 2, 2)), // g
            hexdec(substr($hash, 4, 2))); //b
    }
}
