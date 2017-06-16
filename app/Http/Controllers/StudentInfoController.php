<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Model\StudentInfo;

class StudentInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id)
    {
        $data = [];
        $data['user'] = User::find($user_id);
        // return response()->json($data);

        return view('studentinfo.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $input = $request->only([
            'student_std_id', 'locality'
        ]);

        $studentinfo = StudentInfo::create([
            'user_id' => $user_id,
            'student_id' => $input['student_std_id'],
            'locality' => $input['locality']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {
        $data = [];
        $data['user'] = User::find($user_id);

        return view('studentinfo.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        $input = $request->only([
            'student_std_id', 'locality'
        ]);

        $studentinfo = StudentInfo::where('user_id', $user_id)->first();
        $studentinfo->update([
            'student_id' => $input['student_std_id'],
            'locality' => $input['locality']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
