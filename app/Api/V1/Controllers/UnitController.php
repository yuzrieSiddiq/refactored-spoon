<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\Model\Student;
use App\Model\Unit;
use Dingo\Api\Routing\Helpers;

class UnitController extends Controller
{
    use Helpers;

    public function index()
    {
        $auth_user = JWTAuth::parseToken()->authenticate();
        $this_student = Student::with('unit', 'user')
            ->where('user_id', $auth_user->id)
            ->first();

        $unit = Unit::find($this_student->unit->id);

        return response()->json($unit);
    }

    public function show($unit_id)
    {
        $unit = Unit::with('unit_contents')->find($unit_id);

        return response()->json($unit);
    }
}
