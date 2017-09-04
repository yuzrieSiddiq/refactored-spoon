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

        $students = Student::with('unit', 'user')
            ->where('unit_id', $quiz_individual->unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->get();

        $answers = [];
        foreach ($students as $student) {
            $student_answers = StudentAnswer::where('quiz_id', $quiz_individual->id)
                ->where('student_id', $student->id)
                ->get();

            // if already attempted - by checking if the first object of the array is empty
            if (!empty($student_answers[0])) {
                array_push($answers, $student_answers);
            }
        }

        $data = [];
        $data['quiz'] = $quiz_group;
        $data['quiz_individual'] = $quiz_individual;
        $data['rankings'] = $rankings;
        $data['students'] = $students;
        $data['answers'] = $answers;

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

        $correct_count = 0;
        $wrong_count = 0;

        // NOTE: may require change
        if (isset($answers)) {
            foreach ($answers as $answer) {
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
        }
        $data['correct_answers'] = $correct_count;
        $data['wrong_answers'] = $wrong_count;

        return response()->json($data);
    }

    /**
     * AJAX CALL - POST
     */
    public function group_results($quiz_id, $group_id)
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $quiz = Quiz::find($quiz_id);
        $group= Group::find($group_id);
        $questions = Question::where('quiz_id', $quiz->id)->get();

        // get all students under this group -> this sem, year
        // get all students under this unit -> this sem, year

        // add filter
        $answer_list = StudentAnswer::with(['question',
            'student' => function($query) use ($year, $semester, $group) {
                $query->where('year', $year)
                    ->where('semester', $semester)
                    ->where('group_number', $group->group_number)
                    ->get();
        }])->get();

        return response()->json($answer_list);
    }
}
