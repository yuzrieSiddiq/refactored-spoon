<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Settings;
use App\Model\Quiz;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $input = $request->only(['settings-semester', 'settings-year']);

        // fetch the correct settings and update the correct fields
        $semester = Settings::where('name', 'semester')->first();
        $year = Settings::where('name', 'year')->first();

        // get the quizzes
        $quizzes = Quiz::where('semester', $semester->value)->where('year', $year->value)->get();

        // update the settings
        $semester->update([ 'value' => $input['settings-semester'] ]);
        $year->update([ 'value' => $input['settings-year'] ]);

        /** Copy over the quizzes to the next semester if exist **/
        foreach ($quizzes as $quiz) {
            Quiz::firstOrCreate([
                'unit_id'           => $quiz->unit_id,
                'title'             => $quiz->title ,
                'type'              => $quiz->type,
                'semester'          => $semester->value,
                'year'              => $year->value,
                'show_questions'    => $quiz->show_questions,
                'individual_only'   => $quiz->individual_only,
            ]);
        }
    }
}
