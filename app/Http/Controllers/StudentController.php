<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Unit;
use App\Model\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($unit_id)
    {
        $data = [];

        $unit = Unit::find($unit_id);
        $data['unit'] = $unit;

        return view ('student.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($unit_id)
    {
        $data = [];

        $unit = Unit::find($unit_id);
        $data['unit'] = $unit;
        
        return view ('student.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $unit_id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($unit_id, $student_id)
    {
        $data = [];
        $data['student'] = Student::find($student_id);

        return view ('student.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($unit_id, $student_id)
    {
        $data = [];
        $data['student'] = Student::find($student_id);

        return view ('student.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $unit_id, $student_id)
    {
        $input = $request->only([
            'user_id', 'unit_id', 'semester', 'year', 'team_number', 'is_group_leader'
        ]);
        $student = Student::find($student_id);
        $student->update([
            'user_id' => $input['user_id'],
            'unit_id' => $input['unit_id'],
            'semester' => $input['semester'],
            'year' => $input['year'],
            'team_number' => $input['team_number'],
            'is_group_leader' => $input['is_group_leader']
        ]);

        return 'updated';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($unit_id, $student_id)
    {
        $student = Student::find($student_id);
        $student->delete();

        return 'deleted';
    }
}
