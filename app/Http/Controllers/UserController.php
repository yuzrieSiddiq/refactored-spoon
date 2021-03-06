<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Unit;
use App\Model\StudentInfo;
use App\Model\LecturerUnit;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['availableroles'] = Role::with('permissions')->get();

        return view ('user.create', $data);
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
            'role', 'first_name', 'last_name', 'password','email'
        ]);

        // if check the email - if exist, return '2'
        $check_user_exist_email = User::where('email', $input['email'])->first();
        if (isset($check_user_exist_email)) return 'Error 2';

        // if no error, create students and assign role
        $user = User::create([
            'firstname' => $input['first_name'],
            'lastname' => $input['last_name'],
            'password' => bcrypt($input['password']),
            'email' => $input['email']
        ]);
        $user->assignRole($input['role']);

        return route('users.create.studentinfo', $user->id);
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
        $data['user'] = User::find($id);

        return view ('user.show', $data);
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
        $data['user'] = User::find($id);
        $data['lecturerunits'] = LecturerUnit::with('unit')->where('user_id', $data['user']->id)->get();
        $data['availableunits'] = Unit::all();

        if ($data['user']->roles()->pluck('name')[0] == 'Student') {
            $data['student_info'] = StudentInfo::where('user_id', $id)->first();
        }

        return view ('user.edit', $data);
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
            'role', 'first_name', 'last_name', 'email', 'student_std_id', 'locality'
        ]);

        // check if any fields is empty and return error if true
        foreach ($input as $data) {
            if (empty($data)) {
                // empty parameters
                return 'Error_E01';
            }
        }

        // get user based on the selected id
        $user = User::find($id);

        // find the user based on the parameter id
        $check_user_exist_email = User::where('email', $input['email'])->first();
        if (isset($check_user_exist_email) && $check_user_exist_email->id != $user->id)
            return '2';

        // student - basic user info
        $user->update([
            'firstname' => $input['first_name'],
            'lastname' => $input['last_name'],
            'email' => $input['email']
        ]);
        $user->syncRoles([ $input['role'] ]);

        // student info
        $check_studentinfo_exist = StudentInfo::where('user_id', $user->id)->first();
        if (isset($check_studentinfo_exist)) {
            $check_studentinfo_exist->update([
                'user_id' => $user->id,
                'student_id' => $input['student_std_id'],
                'locality' => $input['locality']
            ]);
        } else {
            $studentinfo = StudentInfo::create([
                'user_id' => $user->id,
                'student_id' => $input['student_std_id'],
                'locality' => $input['locality']
            ]);
        }

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
        $user = User::find($id);
        $user->delete();

        return 'deleted';
    }

    public function edit_password($id)
    {
        $data = [];
        $data['user'] = User::find($id);

        return view ('user.edit_password', $data);
    }

    public function update_password(Request $request, $id)
    {
        $input = $request->only(['password']);
        $user = User::find($id);
        $user->update([
            'password' => bcrypt($input['password'])
        ]);

        return 'ok';
    }
}
