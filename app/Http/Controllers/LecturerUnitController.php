<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view ('lecturer_unit.create');
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
        $data['unit'] = Unit::find($id);

        return view ('lecturer_unit.show', $data);
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

        return view ('lecturer_unit.edit', $data);
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

    public function uploadLecturers(Request $request)
    {
        $input = $request->only([ 'file' ]);
        $lecturers = json_decode($input['file']);

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
