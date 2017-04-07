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
    public function index($quiz_id)
    {
        $data = [];

        $quiz = Quiz::find($quiz_id);
        $questions = Question::where('quiz_id', $quiz_id)->get();
        $data['quiz'] = $quiz;
        $data['questions'] = $questions;

        return view ('question.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($quiz_id)
    {
        $data = [];
        $data['quiz'] = Quiz::find($quiz_id);

        return view ('question.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $quiz_id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($quiz_id, $question_id)
    {
        $data = [];
        $data['question'] = Question::find($question_id);
        $data['quiz'] = Quiz::find($quiz_id);

        return view ('question.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($quiz_id, $question_id)
    {
        $data = [];
        $data['question'] = Question::find($question_id);
        $data['quiz'] = Quiz::find($quiz_id);

        return view ('question.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $quiz_id, $question_id)
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
    public function destroy($quiz_id, $question_id)
    {
        $question = Question::find($id);
        $question->delete();

        return 'deleted';
    }
}
