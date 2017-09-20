<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Settings;
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
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $input = $request->only([ 'student_user_id' ]);

        Student::create([
            'user_id' => $input['student_user_id'],
            'unit_id' => $unit_id,
            'semester'=> $semester,
            'year'    => $year,
            'team_number' => null,
            'group_number' => null,
            'is_group_leader' => false,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($unit_id, $student_id)
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $data = [];
        $data['this_student'] = Student::with('user')->find($student_id);
        $data['this_student_info'] = StudentInfo::where('user_id', $data['this_student']->user->id)->first();
        $data['this_unit'] = Unit::find($unit_id);

        $data['all_students'] = Student::where('unit_id', $unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->get();
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
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $input = $request->only(['is_leader']); // 0 or 1

        // counting teams and assigning new team
        $newteam = 0;
        $teams = Student::where('unit_id', $unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->where('is_group_leader', true)
            ->get();
        foreach ($teams as $team) {
            if ($team->team_number != null) {
                if ($team->team_number > $newteam) {
                    $newteam = $team->team_number;
                }
            }
        }
        $newteam += 1;

        $student = Student::find($student_id);
        if ($input['is_leader']) {
            // if is a leader, do: revoke as leader
            $team_members = Student::where('unit_id', $student->unit_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->where('team_number', $student->team_number)
                ->where('is_group_leader', false)->get();

            // if this team do not have members, cancel the team
            if (count($team_members) < 1) {
                $student->update([
                    'team_number' => null,
                    'is_group_leader' => false
                ]);
            } else {
                // else if the team is active, assign next person as the team leader
                $student->update([
                    'team_number' => $student->team_number,
                    'is_group_leader' => false
                ]);

                $team_members[0]->update([
                    'team_number' => $team_members[0]->team_number,
                    'is_group_leader' => true
                ]);
            }
        } else {
            // if not a leader, do: assign as leader

            // check if theres existing leader in the team
            $leader = Student::where('unit_id', $student->unit_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->where('team_number', $student->team_number)
                ->where('is_group_leader', true)
                ->first();

            // if leader does not exist, assign this student as the leader
            if (!isset($leader)) {
                $student->update([
                    'team_number' => $newteam,
                    'is_group_leader' => true
                ]);
            } else {
                // if leader exist, swap the leader position to this student
                $leader->update(['is_group_leader' => false]);
                $student->update(['is_group_leader' => true]);
            }
        }
    }

    public function update_group_no(Request $request, $unit_id, $student_id)
    {
        // return response()->json($request);
        $student = Student::find($student_id);
        $student->update([
            'group_number' => $request['group_no'],
        ]);
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
        $input = $request->only([ 'file', 'unit_code' ]);
        $students = json_decode($input['file']);
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        // headers error checking - return Error_H01
        if (count($students[0]) != 16) {
            return response()->json("Error_H01");
        }

        // start appending
        foreach ($students as $row) {
            // at the end of the file, it always append an empty line
            if ($row[0] == '') {
                break;
            }

            // add user entry
            $user = User::where('email', $row[2])->first();
            if (!isset($user)) {
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
            }

            // find if the units specified exist
            $units = [];
            for ($i=6; $i < 11; $i++) {
                $units[$i] = Unit::where('code', $row[$i])->first();
                $unit_group_number = $row[$i + 5];

                // check unit - only add those with the assigned unit
                if ($units[$i]['code'] == $input['unit_code']) {

                    $student = Student::where('user_id', $user->id)
                        ->where('unit_id', $units[$i]['id'])
                        ->where('semester', $semester)
                        ->where('year', $year)
                        ->first();

                    if (!isset($student)) {
                        Student::create([
                            'user_id' => $user->id,
                            'unit_id' => $units[$i]['id'],
                            'semester'=> 'S1',
                            'year'    => 2017,
                            'team_number' => null,
                            'group_number' => $unit_group_number,
                            'is_group_leader' => false,
                        ]);
                    }
                }
            }
        }
    }
}
