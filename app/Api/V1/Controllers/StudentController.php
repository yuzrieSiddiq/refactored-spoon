<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\Model\Student;
use App\Model\Unit;
use Dingo\Api\Routing\Helpers;

class StudentController extends Controller
{
    use Helpers;

    public function index()
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->get();

        return response()->json($this_student);
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
            ->get();

        $available_students = Student::with('user')
            ->where('unit_id', $this_student->unit->id)
            ->where('semester', $this_student->semester)
            ->where('year', $this_student->year)
            ->whereNull('team_number')
            ->get();

        $data['this_team'] = $this_team;
        $data['this_student'] = $this_student;
        $data['available_students'] = $available_students;

        return response()->json($data);
    }

    public function enlist_new_member($student_id)
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
}
