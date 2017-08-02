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

        $data['has_empty_answer_type'] = false;
        foreach ($questions as $question) {
            if (empty($question->answer_type)) {
                $data['has_empty_answer_type'] = true;
                break;
            }
        }

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
        $input = $request->only([
            'quiz_title', 'question', 'correct_answer',
            'answer1', 'answer2', 'answer3', 'answer4', 'answer5'
        ]);

        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        // create questions for group quiz
        $questions_group = Question::create([
            'quiz_id' => $quiz_group->id,
            'question' => $input['question'],
            'answer1' => $input['answer1'],
            'answer2' => $input['answer2'],
            'answer3' => $input['answer3'],
            'answer4' => $input['answer4'],
            'answer5' => $input['answer5'],
            'correct_answer' => $input['correct_answer'],
        ]);

        // create questions for individual quiz
        $questions_individual = Question::create([
            'quiz_id' => $quiz_individual->id,
            'question' => $input['question'],
            'answer1' => $input['answer1'],
            'answer2' => $input['answer2'],
            'answer3' => $input['answer3'],
            'answer4' => $input['answer4'],
            'answer5' => $input['answer5'],
            'correct_answer' => $input['correct_answer'],
        ]);
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
            'question', 'answer1', 'answer2', 'answer3',
            'answer4', 'answer5', 'correct_answer'
        ]);

        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        // get questions for group and individual quiz
        $question_group = Question::find($question_id);
        $questions_individual = Question::where('quiz_id', $quiz_individual->id)
            ->where('question', $question_group->question)
            ->first();

        $question_group->update([
            'question' => $input['question'],
            'answer1' => $input['answer1'],
            'answer2' => $input['answer2'],
            'answer3' => $input['answer3'],
            'answer4' => $input['answer4'],
            'answer5' => $input['answer5'],
            'correct_answer' => $input['correct_answer'],
        ]);

        $questions_individual->update([
            'question' => $input['question'],
            'answer1' => $input['answer1'],
            'answer2' => $input['answer2'],
            'answer3' => $input['answer3'],
            'answer4' => $input['answer4'],
            'answer5' => $input['answer5'],
            'correct_answer' => $input['correct_answer'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($quiz_id, $question_id)
    {
        $quiz_group = Quiz::find($quiz_id);

        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $question_group = Question::find($question_id);
        $questions_individual = Question::where('quiz_id', $quiz_individual->id)
            ->where('question', $question_group->question)
            ->first();

        $question_group->delete();
        $questions_individual->delete();
    }

    public function destroy_all($quiz_id)
    {
        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $questions_group = Question::where('quiz_id', $quiz_group->id)->get();
        $questions_individual = Question::where('quiz_id', $quiz_individual->id)->get();

        foreach ($questions_group as $question)
            $question->delete();

        foreach ($questions_individual as $question)
            $question->delete();
    }

    public function uploadQuestions(Request $request, $quiz_id)
    {
        $input = $request->only([ 'file' ]);
        $questions = json_decode($input['file']);

        $quiz_group = Quiz::find($quiz_id);

        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        foreach ($questions as $row) {
            if ($row[0] == '') {
                break;
            }
            // add entry for each column
            $all_is_set = true;
            for ($i=1; $i < 8; $i++) {
                // if unit found, then add the students to that unit
                if (!isset($row[6])) {
                    $row[6] = '-';
                }
                if (!isset($row[$i])) {
                    $all_is_set = false;
                }
            }
            if ($all_is_set) {
                Question::create([
                    'quiz_id' => $quiz_group->id,
                    'answer_type' => 'MCQ',
                    'question'=> $row[1],
                    'answer1' => $row[2],
                    'answer2' => $row[3],
                    'answer3' => $row[4],
                    'answer4' => $row[5],
                    'answer5' => $row[6],
                    'correct_answer' => $row[7],
                ]);

                Question::create([
                    'quiz_id' => $quiz_individual->id,
                    'answer_type' => 'MCQ',
                    'question'=> $row[1],
                    'answer1' => $row[2],
                    'answer2' => $row[3],
                    'answer3' => $row[4],
                    'answer4' => $row[5],
                    'answer5' => $row[6],
                    'correct_answer' => $row[7],
                ]);
            } else {
                return '2';
            }
        }

        return 'ok';
    }
}
