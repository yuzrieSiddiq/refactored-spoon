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
        try {
            return $result = Excel::load("public/upload/test.xls",function($reader){
                //
            })->get();
        } catch (Exception $e) {
            echo "<br>".$e->getMessage();
        }
        // $input = $request->only([ 'file' ]);
        //
        // return Excel::load($request['file'], function($reader) { })->get();

        // return response()->json($path);
    }
}
