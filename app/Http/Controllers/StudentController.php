<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Quiz;
use App\Model\Unit;
use App\Model\Question;
use App\Model\Student;
use App\Model\StudentInfo;
use App\Model\StudentAnswer;
use App\User;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($unit_id)
    {
        $data = [];

        $unit = Unit::find($unit_id);
        $students = User::role('Student')->with('student_info', 'students')->get();

        $data['unit'] = $unit;
        $data['students'] = $students;

        // return response()->json($data);

        return view ('student.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($unit_id)
    {
        // $data = [];
        // return view ('student.create', $data);
        // empty
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $unit_id)
    {
        $input = $request->only([
            'student_user_id', 'semester', 'year'
        ]);

        Student::create([
            'user_id' => $input['student_user_id'],
            'unit_id' => $unit_id,
            'semester'=> $input['semester'],
            'year'    => $input['year'],
            'team_number' => null,
            'is_group_leader' => false,
        ]);

        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($unit_id, $student_id)
    {
        $data = [];
        $data['this_student'] = Student::with('user')->find($student_id);
        $data['this_student_info'] = StudentInfo::where('user_id', $data['this_student']->user->id)->first();
        $data['this_unit'] = Unit::find($unit_id);

        $data['all_students'] = Student::where('unit_id', $unit_id)->get();
        $data['quizzes'] = Quiz::where('unit_id', $unit_id)->get();

        $data['ranking_asc'] = [];
        $data['ranking'] = [];
        foreach ($data['quizzes'] as $quiz) {
            foreach ($data['all_students'] as $student) {
                $answers = StudentAnswer::where('quiz_id', $quiz->id)
                    ->where('student_id', $student->id)
                    ->get();

                if ($answers->count() > 0) {

                    $correct_count = 0;
                    foreach ($answers as $answer) {
                        $question = Question::find($answer->question_id);
                        if ($answer->answer == $question->correct_answer)
                            $correct_count++;
                    }

                    // calculate score in 100%
                    $score = ($correct_count * 100) / count($answers);

                    $ranker = [];
                    $ranker['student_id'] = $student->id;
                    $ranker['quiz_id'] = $quiz->id;
                    $ranker['score'] = $score;
                    $ranker['rank_no'] = 0;

                    array_push($data['ranking'], $ranker);

                } else {

                    $ranker = [];
                    $ranker['student_id'] = $student->id;
                    $ranker['quiz_id'] = $quiz->id;
                    $ranker['score'] = 0;
                    $ranker['rank_no'] = 0;

                    array_push($data['ranking'], $ranker);
                }
            }

            // sort the score in descending - big first --> smaller
            $score = [];
            foreach ($data['ranking'] as $key => $row) {
                $score[$key] = $row['score'];
            }
            array_multisort($score, SORT_DESC, $data['ranking']);

            // increment the rank no if already attempt the quiz
            $current_rank = 0;
            foreach ($data['ranking'] as $ranker) {
                if ($ranker['score'] > 0 && $ranker['quiz_id'] == $quiz->id) {
                    $current_rank++;
                    $ranker['rank_no'] = $current_rank;

                    array_push($data['ranking_asc'], $ranker);
                }
            }
        }

        // return response()->json($data);

        return view ('student.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($unit_id, $student_id)
    {
        $data = [];
        $data['student'] = Student::find($student_id);

        return view ('student.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $unit_id, $student_id)
    {
        $newteam = 0;
        $students = Student::where('unit_id', $unit_id)->where('is_group_leader', true)->get();
        foreach ($students as $student) {
            if ($student->team_number != null) {
                if ($student->team_number > $newteam) {
                    $newteam = $student->team_number;
                }
            }
        }
        $newteam += 1;

        $student = Student::find($student_id);
        $student->update([
            'team_number' => $newteam,
            'is_group_leader' => true
        ]);

        return 'updated';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($unit_id, $student_id)
    {
        $student = Student::find($student_id);
        $student->delete();

        return 'deleted';
    }

    public function uploadStudents(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $students = json_decode($input['file']);

        foreach ($students as $row) {
            // at the end of the file, it always append an empty line
            if ($row[1] == '') {
                break;
            }

            // add user entry
            $check_user_exist = User::where('email', $row[2])->first();
            if (!$check_user_exist) {
                $user = User::create([
                    'firstname' => $row[0],
                    'lastname'  => $row[1],
                    'email'     => $row[2],
                    'password'  => bcrypt($row[4]),
                ]);
                $user->assignRole('Student');

                // add student information -> i.e: student id
                $studentinfo = StudentInfo::create([
                    'user_id'    => $user->id,
                    'student_id' => $row[3],
                    'locality'   => $row[5],
                ]);

                // find if the units specified exist
                $units = [];
                for ($i=0; $i < 5; $i++) {
                    $units[$i] = Unit::where('code', $row[$i+6])->first();

                    // if unit found, then add the students to that unit
                    if (isset($units[$i])) {
                        Student::create([
                            'user_id' => $user->id,
                            'unit_id' => $units[$i]->id,
                            'semester'=> 'S1',
                            'year'    => 2017,
                            'team_number' => null,
                            'is_group_leader' => false,
                        ]);
                    }
                }
            }
        }

        return 'ok';
    }
}
