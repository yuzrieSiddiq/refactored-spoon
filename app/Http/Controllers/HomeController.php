<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Excel;

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
        return view('home');
    }

    public function import(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $rows = json_decode($input['file']);

        foreach ($rows as $row) {
            return response()->json($row);
        }

        // return response()->json($input);
    }
}
