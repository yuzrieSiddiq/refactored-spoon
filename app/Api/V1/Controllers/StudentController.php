<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\User;
use App\Model\Student;
use App\Model\StudentInfo;
use App\Model\Unit;
use Dingo\Api\Routing\Helpers;

class StudentController extends Controller
{
    use Helpers;

    public function index()
    {
        $auth_user = JWTAuth::parseToken()->authenticate();

        $data = [];
        $data['user'] = User::with('student_info')->find($auth_user->id);
        $data['this_student'] = Student::with('unit')
            ->where('user_id', $auth_user->id)
            ->get();

        return response()->json($data);
    }

    public function team_info($unit_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();

        $this_unit = Unit::find($unit_id);
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $this_unit->id)
            ->first();

        $this_team = Student::with('user')
            ->where('unit_id', $this_student->unit->id)
            ->where('semester', $this_student->semester)
            ->where('year', $this_student->year)
            ->where('team_number', $this_student->team_number)
            ->get();

        $available_students = Student::with('user')
            ->where('unit_id', $this_student->unit->id)
            ->where('semester', $this_student->semester)
            ->where('year', $this_student->year)
            ->whereNull('team_number')
            ->get();

        $data['this_student'] = $this_student;

        $data['this_team'] = [];
        foreach ($this_team as $team_member) {
            $detail = [];
            $detail['student_id'] = $team_member->id;
            $detail['team_number'] = $team_member->team_number;
            $detail['is_group_leader'] = $team_member->is_group_leader;
            $detail['user_name'] = $team_member->user->firstname . " " . $team_member->user->lastname;
            $detail['user_id'] = $team_member->user_id;
            $detail['student_std_id'] = StudentInfo::where('user_id',$detail['user_id'])->first()->student_id;

            array_push($data['this_team'], $detail);
        }

        $data['available_students'] = [];
        foreach ($available_students as $student) {
            $detail = [];
            $detail['student_id'] = $student->id;
            $detail['user_name'] = $student->user->firstname . " " . $student->user->lastname;
            $detail['user_id'] = $student->user_id;
            $detail['student_std_id'] = StudentInfo::where('user_id',$detail['user_id'])->first()->student_id;

            array_push($data['available_students'], $detail);
        }

        return response()->json($data);
    }

    public function enlist_new_member($student_id, $unit_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();

        $this_unit = Unit::find($unit_id);
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $this_unit->id)
            ->first();

        $new_member = Student::find($student_id);
        $new_member->update([ 'team_number' => $this_student->team_number ]);

        return 'new member added';
    }

    public function delist_member($student_id, $unit_id)
    {
        $auth_user = JWTAuth::parseToken()->authenticate();

        $this_unit = Unit::find($unit_id);
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->where('unit_id', $this_unit->id)
            ->first();

        // selected to be removed
        $selected_member = Student::find($student_id);
        $selected_member->update([ 'team_number' => null ]);

        return 'new member added';
    }

    public function student_units()
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit')
            ->where('user_id', $auth_user->id)
            ->get();

        return response()->json($this_student);
    }
}
