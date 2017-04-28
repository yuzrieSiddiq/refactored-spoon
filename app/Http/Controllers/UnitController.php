<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Model\Quiz;
use App\Model\Unit;
use App\Model\LecturerUnit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('unit.index');
    }

    public function index_lecturer()
    {
        $data = [];

        $user = Auth::user();
        $data['units'] = LecturerUnit::with('unit')->where('user_id', $user->id)->get();

        return view ('unit.index_lecturer', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only([
            'code', 'name', 'description'
        ]);

        $check_unit_exist_code = Unit::where('code', $input['code'])->first();
        if (isset($check_unit_exist_code)) return '1';

        $check_unit_exist_name = Unit::where('name', $input['name'])->first();
        if (isset($check_unit_exist_name)) return '2';

        Unit::create([
            'code' => $input['code'],
            'name' => $input['name'],
            'description' => $input['description'],
        ]);

        return 'created';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $data['unit'] = Unit::with('students','quizzes')->find($id);
        $data['quizzes'] = Quiz::where('unit_id', $data['unit']->id)
            ->where('semester', 'S1')->where('year', 2017)->get();

        return view ('unit.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data['unit'] = Unit::find($id);

        return view ('unit.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->only([
            'code', 'name', 'description'
        ]);

        $unit = Unit::find($id);
        $unit->update([
            'code' => $input['code'],
            'name' => $input['name'],
            'description' => $input['description'],
        ]);

        return 'updated';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);
        $unit->delete();

        return 'deleted';
    }
}
