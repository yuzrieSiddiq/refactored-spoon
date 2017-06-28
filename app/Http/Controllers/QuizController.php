<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Unit;
use App\Model\Quiz;
use App\Model\Question;

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

        // create individual quiz
        Quiz::create([
            'unit_id' => $unit->id,
            'semester' => $input['semester'],
            'year' => $input['year'],
            'title' => $input['title'],
            'type' => 'individual',
            'status' => 'open',
        ]);

        // create group quiz
        Quiz::create([
            'unit_id' => $unit->id,
            'semester' => $input['semester'],
            'year' => $input['year'],
            'title' => $input['title'],
            'type' => 'group',
            'status' => 'open',
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
        $quiz_group = Quiz::find($id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $quiz_group->update([
            'semester' => $input['semester'],
            'year' => $input['year'],
            'title' => $input['title'],
            'status' => $input['status']
        ]);

        $quiz_individual->update([
            'semester' => $input['semester'],
            'year' => $input['year'],
            'title' => $input['title'],
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
        $quiz_group = Quiz::find($id);
        $quiz_group->delete();

        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();
        $quiz_individual->delete();

        return 'deleted';
    }

    public function create_upload()
    {
        return view ('quiz.create_upload');
    }

    public function store_upload(Request $request)
    {
        $input = $request->only([
            'file', 'title', 'semester',
            'year', 'type', 'status'
        ]);
        $questions = json_decode($input['file']);

        foreach ($questions as $row) {
            if ($row[0] == '') {
                break;
            }

            // find the unit and make sure it exist
            $unit = Unit::where('code', $row[0])->first();
            if (isset($unit)) {

                $quiz = Quiz::where('unit_id', $unit->id)
                    ->where('title', $input['title'])
                    ->where('semester', $input['semester'])
                    ->where('year', $input['year'])
                    ->where('type', $input['type'])
                    ->first();

                if (!isset($quiz)) {
                    $quiz = Quiz::create([
                        'unit_id' => $unit->id,
                        'semester' => $input['semester'],
                        'year' => $input['year'],
                        'title' => $input['title'],
                        'type' => $input['type'],
                        'status' => $input['status']
                    ]);
                }

                // add entry for each column
                $all_is_set = true;
                for ($i=1; $i < 8; $i++) {
                    // if answer 5 is empty, keep it -
                    if (!isset($row[6])) {
                        $row[6] = '-';
                    }
                    if (!isset($row[$i])) {
                        $all_is_set = false;
                    }
                }
                if ($all_is_set) {

                    $question = Question::where('quiz_id', $quiz->id)
                        ->where('question', $row[1])
                        ->first();

                    if (!isset($question)) {
                        Question::create([
                            'quiz_id' => $quiz->id,
                            'answer_type' => '',
                            'question'=> $row[1],
                            'answer1' => $row[2],
                            'answer2' => $row[3],
                            'answer3' => $row[4],
                            'answer4' => $row[5],
                            'answer5' => $row[6],
                            'correct_answer' => $row[7],
                        ]);
                    }
                } else {
                    return '2';
                }

            } else {
                // if unit is not exist
                // skip, todo: add to a list to show as error
            }
        }

        return 'ok';
    }
}
