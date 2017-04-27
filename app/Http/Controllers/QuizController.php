<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Unit;
use App\Model\Quiz;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('quiz.index');
    }

    public function index_unit($unit_id)
    {
        $data = [];
        $data['unit'] = Unit::find($unit_id);
        $data['quizzes'] = Quiz::where('unit_id', $unit_id)
            ->where('semester', 'S1')->where('year', 2017)->get();

        return view ('unit.index_quiz', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['units'] = Unit::all();

        return view ('quiz.create', $data);
    }

    public function create_unit($unit_id)
    {
        $data = [];
        $data['unit'] = Unit::find($unit_id);

        return view ('quiz.create', $data);
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
            'unit_code', 'semester', 'year', 'title', 'type', 'status'
        ]);

        $unit = Unit::where('code', $input['unit_code'])->first();

        Quiz::create([
            'unit_id' => $unit->id,
            'semester' => $input['semester'],
            'year' => $input['year'],
            'title' => $input['title'],
            'type' => $input['type'],
            'status' => $input['status']
        ]);

        return 'ok';
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
        $data['quiz'] = Quiz::find($id);

        return view ('quiz.show', $data);
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
        $data['quiz'] = Quiz::find($id);

        return view ('quiz.edit', $data);
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
            'semester', 'year', 'title', 'type', 'status'
        ]);
        $quiz = Quiz::find($id);
        $quiz->update([
            'semester' => $input['semester'],
            'year' => $input['year'],
            'title' => $input['title'],
            'type' => $input['type'],
            'status' => $input['status']
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
        $quiz = Quiz::find($id);
        $quiz->delete();

        return 'deleted';
    }
}
