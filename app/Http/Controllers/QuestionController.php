<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Question;
use App\Model\Quiz;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('question.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('question.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data['question'] = Question::find($id);

        return view ('question.show', $data);
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
        $data['question'] = Question::find($id);
        $data['quiz'] = Quiz::find($data['question']->quiz_id);

        return view ('question.edit', $data);
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
            'question', 'answer_type', 'answer1', 'answer2', 'answer3',
            'answer4', 'answer5', 'correct_answer'
        ]);

        $question = Question::find($id);
        $question->update([
            'question' => $input['question'],
            'answer_type' => $input['answer_type'],
            'answer1' => $input['answer1'],
            'answer2' => $input['answer2'],
            'answer3' => $input['answer3'],
            'answer4' => $input['answer4'],
            'answer5' => $input['answer5'],
            'correct_answer' => $input['correct_answer']
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
        $question = Question::find($id);
        $question->delete();

        return 'deleted';
    }
}
