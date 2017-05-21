<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Unit;
use App\Model\Quiz;
use App\Model\Student;

class ReportingController extends Controller
{
    /**
     * GET unit report
     * return view()
     */
    public function unit_report($unit_id)
    {
        $data = [];
        $data['unit'] = Unit::find($unit_id);

        return view('report.unit', $data);
    }

    /**
     * GET quiz report + ranking
     * return view()
     */
    public function quiz_report($quiz_id)
    {
        $data = [];
        $data['quiz'] = Quiz::find($quiz_id);

        return view('report.quiz', $data);
    }

    /**
     * GET student report
     * return view()
     */
    public function student_report($student_id)
    {
        $data = [];
        $data['student'] = Student::with(['user' => function($query) {
            $query->with('student_info')->get();
        }])->find($student_id);

        return view('report.student', $data);
    }
}
