<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Unit;
use App\Model\Quiz;
use App\Model\Question;
use App\Model\Settings;
use App\Model\Student;
use App\Model\Group;

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
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $data = [];
        $data['unit'] = Unit::find($unit_id);
        $data['quizzes'] = Quiz::where('unit_id', $unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->get();

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
            'unit_code', 'semester', 'year', 'title', 'type'
        ]);

        $unit = Unit::where('code', $input['unit_code'])->first();
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        if ($input['type'] == 'both') {
            // create both quizzes
            $quiz_individual = Quiz::create([
                'unit_id' => $unit->id,
                'semester' => $semester,
                'year' => $year,
                'title' => $input['title'],
                'type' => 'individual',
                'show_questions' => 0,
                'individual_only' => false,
            ]);

            $quiz_group = Quiz::create([
                'unit_id' => $unit->id,
                'semester' => $semester,
                'year' => $year,
                'title' => $input['title'],
                'type' => 'group',
                'show_questions' => 0,
                'individual_only' => false,
            ]);

        } else if ($input['type'] == 'individual') {
            // create individual quiz only
            $quiz_individual = Quiz::create([
                'unit_id' => $unit->id,
                'semester' => $semester,
                'year' => $year,
                'title' => $input['title'],
                'type' => 'individual',
                'show_questions' => 0,
                'individual_only' => true,
            ]);
        }

        // group here as in tutorial class groups
        $group_count = 0;
        $students = Student::where('unit_id', $quiz_individual->unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->orderBy('group_number')
            ->get();
        foreach ($students as $student) {
            if ($group_count < $student->group_number) {
                $group_count = $student->group_number;

                // group for individual quiz only
                $group_individual = Group::where('quiz_id', $quiz_individual->id)
                    ->where('group_number', $group_count)
                    ->first();

                if (!isset($group_individual)) {
                    Group::create([
                        'quiz_id' => $quiz_individual->id,
                        'group_number' => $group_count,
                        'is_open' => false,
                        'is_randomized' => true,
                        'test_date' => null,
                        'duration' => null,
                        'chosen_questions' => null,
                    ]);
                }

                // if quiz is not for both, only create groups for individual quizzes
                // group : tutorial group, _group = type of quiz -> individual/group
                if ($input['type'] == 'both') {
                    $group_group = Group::where('quiz_id', $quiz_group->id)
                    ->where('group_number', $group_count)
                    ->first();

                    if (!isset($group_group)) {
                        Group::create([
                            'quiz_id' => $quiz_group->id,
                            'group_number' => $group_count,
                            'is_open' => false,
                            'is_randomized' => true,
                            'test_date' => null,
                            'duration' => null,
                            'chosen_questions' => null,
                        ]);
                    }
                }
            }
        }
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
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $quiz_group = Quiz::find($id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $data = [];
        $data['quiz'] = $quiz_group;
        $data['quiz_individual'] = $quiz_individual;

        // get the number of groups available based on students list
        $data['group_count'] = 0;
        $data['groups_individual'] = [];
        $data['groups_group'] = [];
        $students = Student::where('unit_id', $data['quiz']->unit_id)
            ->where('semester', $semester)
            ->where('year', $year)
            ->orderBy('group_number')
            ->get();

        foreach ($students as $student) {
            if ($data['group_count'] < $student->group_number) {
                $data['group_count'] = $student->group_number;

                $group_individual = Group::where('quiz_id', $quiz_individual->id)
                    ->where('group_number', $data['group_count'])
                    ->first();

                $group_group = Group::where('quiz_id', $quiz_group->id)
                    ->where('group_number', $data['group_count'])
                    ->first();

                array_push($data['groups_individual'], $group_individual);
                array_push($data['groups_group'], $group_group);
            }
        }

        // return response()->json($data);

        return view ('quiz.edit', $data);
    }

    public function edit_tutorial_group($quiz_id, $group_no)
    {
        $data = [];
        $quiz = Quiz::with('questions')->find($quiz_id);
        $tutorial_group = Group::where('quiz_id', $quiz->id)
            ->where('group_number', $group_no)
            ->first();

        $data = [];
        $data['quiz'] = $quiz;
        $data['group'] = $tutorial_group;

        // return response()->json($data);
        return view ('quiz.edit_tutorial_groups', $data);
    }

    public function update_tutorial_group(Request $request, $quiz_id, $group_no)
    {
        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $group_group = Group::where('quiz_id', $quiz_group->id)
            ->where('group_number', $group_no)
            ->first();
        $group_individual = Group::where('quiz_id', $quiz_individual->id)
            ->where('group_number', $group_no)
            ->first();

        $date = Carbon::createFromFormat('d/m/Y', $request['date']);

        $is_open = ($request['is_open'] == 'true');
        $is_random = ($request['is_randomized'] == 'true');

        $group_group->update([
            'is_open' => $is_open,
            'is_randomized' => $is_random,
            'duration' => $request['duration'],
            'test_date' => $date,
        ]);

        $group_individual->update([
            'is_open' => $is_open,
            'is_randomized' => $is_random,
            'duration' => $request['duration'],
            'test_date' => $date,
        ]);
    }

    public function choose_questions(Request $request, $quiz_id, $group_no)
    {
        $quiz_group = Quiz::find($quiz_id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $group_group = Group::where('quiz_id', $quiz_group->id)
            ->where('group_number', $group_no)
            ->first();
        $group_individual = Group::where('quiz_id', $quiz_individual->id)
            ->where('group_number', $group_no)
            ->first();

        $group_group->update([
            'chosen_questions' => $request['chosen_questions'],
        ]);

        $group_individual->update([
            'chosen_questions' => $request['chosen_questions'],
        ]);
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
            'title', 'show_questions', 'groups_schedule'
        ]);
        $quiz_group = Quiz::find($id);
        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();

        $quiz_group->update([
            'title' => $input['title'],
            'show_questions' => $input['show_questions'],
        ]);

        $quiz_individual->update([
            'title' => $input['title'],
            'show_questions' => $input['show_questions'],
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
        $quiz_group = Quiz::find($id);
        $quiz_group->delete();

        $quiz_individual = Quiz::where('unit_id', $quiz_group->unit_id)
            ->where('semester', $quiz_group->semester)
            ->where('year', $quiz_group->year)
            ->where('title', $quiz_group->title)
            ->where('type', 'individual')
            ->first();
        $quiz_individual->delete();
    }
}
