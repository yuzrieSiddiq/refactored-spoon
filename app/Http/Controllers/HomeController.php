<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

use App\Model\Unit;
use App\Model\Quiz;

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

        switch ($user->roles()->pluck('id')[0]) {
            case '1':
                return view ('home.student');

            case '2':
                $data = Self::lecturer($user);
                return view ('home.lecturer', $data);

            case '3':
                $data = Self::admin($user);
                return view ('home.admin');

            default:
                return view('home');
        }
    }

    public function lecturer($user)
    {
        $data = [];
        $data['units'] = Unit::with('students','quizzes')->get();

        return $data;
    }

    public function admin($user)
    {
        $data = [];

        return $data;
    }

    /**
     * The csv file does not include headers, simply starts from real data, headers works as follows:
     * 1) importLecturer: firstname, lastname, email, password
     * 2) importStudent: firstname, lastname, email, student id, password, local/international, unitcode n
     * 3) importQuestions: unitcode, questions, ans n, correct answer
     **/
    public function uploadLecturers(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $lecturers = json_decode($input['file']);

        foreach ($lecturers as $row) {
            return response()->json($row);
        }

        // return 'ok';
    }

    public function uploadStudents(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $students = json_decode($input['file']);

        foreach ($students as $row) {
            return response()->json($row);
        }

        // return 'ok';
    }

    public function uploadQuestions(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $questions = json_decode($input['file']);

        foreach ($questions as $row) {
            return response()->json($row);
        }

        // return 'ok';
    }
}
