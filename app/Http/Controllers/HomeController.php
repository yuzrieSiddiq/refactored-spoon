<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

use App\Model\Unit;
use App\Model\Quiz;
use App\Model\LecturerUnit;
use App\Model\Settings;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $my_units = LecturerUnit::where('user_id', $user->id)->get();
        // $my_units = LecturerUnit::all();
        // return response()->json($my_units);

        switch ($user->roles()->pluck('id')[0]) {
            case '1':
                $data = Self::admin($user);
                return view ('home.admin', $data);

            case '2':
                $data = Self::lecturer($user);
                return view ('home.lecturer', $data);

            case '3':
                return view ('home.student');

            default:
                return view('home');
        }
    }

    public function admin($user)
    {
        $data = [];
        $data['semester'] = Settings::where('name', 'semester')->first();
        $data['year'] = Settings::where('name', 'year')->first();

        return $data;
    }

    public function lecturer($user)
    {
        $data = [];
        $data['units'] = [];

        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        // get all of the lecturer's units
        $my_units = LecturerUnit::where('user_id', $user->id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->get();

        // get all units
        $units = Unit::with('students','quizzes')->get();
        foreach ($units as $unit) {
            foreach ($my_units as $my_unit) {
                // get only the units assigned to this lecturer
                if ($unit->id == $my_unit->unit_id) {
                    array_push($data['units'], $unit);
                }
            }
        }

        return $data;
    }

    /**
     * The csv file does not include headers, simply starts from real data, headers works as follows:
     * 1) importLecturer: firstname, lastname, email, password
     * 2) importStudent: firstname, lastname, email, student id, password, local/international, unitcode n
     * 3) importQuestions: unitcode, questions, ans n, correct answer
     **/

}
