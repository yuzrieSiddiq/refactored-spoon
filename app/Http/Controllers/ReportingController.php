<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportingController extends Controller
{
    /**
     * GET unit report
     * return view()
     */
    public function unit_report($unit_id)
    {
        return 'overall_unit_report';
    }

    /**
     * GET quiz report
     * return view()
     */
    public function quiz_report($quiz_id)
    {
        return 'quiz_report';
    }

    /**
     * GET student report
     * return view()
     */
    public function student_report($student_id)
    {
        return 'student_report';
    }

    /**
     * GET ranking table
     * return view()
     */
    public function ranking_report()
    {
        return 'ranking_report';
    }
}
