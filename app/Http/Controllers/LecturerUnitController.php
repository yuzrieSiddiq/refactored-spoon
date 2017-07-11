<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Settings;
use App\Model\LecturerUnit;
use App\Model\Unit;
use App\User;

class LecturerUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('lecturer_unit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['lecturers'] = User::role('Lecturer')->get();
        $data['available_units'] = Unit::all();

        return view ('lecturer_unit.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        $input = $request->only(['user_id', 'units']);
        $input_units = json_decode($input['units']);

        $existing_units = LecturerUnit::where('user_id', $input['user_id'])
            ->where('semester', $semester)
            ->where('year', $year)
            ->get();
        foreach ($input_units as $unit) {
            $unit_is_exist = false;
            foreach ($existing_units as $unitexist) {
                if ($unitexist->unit_id == $unit->unit_id) {
                    $unit_is_exist = true;
                    break;
                }
            }

            if (!$unit_is_exist) {
                LecturerUnit::create([
                    'user_id' => $input['user_id'],
                    'unit_id' => $unit->unit_id,
                    'semester'=> $semester,
                    'year'    => $year,
                ]);
            }
        }

        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = LecturerUnit::find($id);
        $unit->delete();

        return 'deleted';
    }

    public function uploadLecturers(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $lecturers = json_decode($input['file']);

        // headers error checking - return Error_H01
        if (count($lecturers[0]) != 4) {
            return response()->json("Error_H01");
        }

        foreach ($lecturers as $row) {
            // at the end of the file, it always append an empty line
            if ($row[0] == '') {
                break;
            }

            // add user entry
            $check_user_exist = User::where('email', $row[2])->first();
            if (!$check_user_exist) {
                $user = User::create([
                    'firstname' => $row[0],
                    'lastname'  => $row[1],
                    'email'     => $row[2],
                    'password'  => bcrypt($row[3]),
                ]);
                $user->assignRole('Lecturer');
            }
        }

        return 'ok';
    }
}
