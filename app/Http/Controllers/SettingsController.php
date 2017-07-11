<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Settings;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $input = $request->only(['settings-semester', 'settings-year']);

        // fetch the correct settings and update the correct fields
        $semester = Settings::where('name', 'semester')->first();
        $semester->update([ 'value' => $input['settings-semester'] ]);

        $year = Settings::where('name', 'year')->first();
        $year->update([ 'value' => $input['settings-year'] ]);

    }
}
