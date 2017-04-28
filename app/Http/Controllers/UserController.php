<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        if (isset($check_user_exist_email)) return '2';

        // if no error, create students and assign role
        $user = User::create([
            'firstname' => $input['first_name'],
            'lastname' => $input['last_name'],
            'password' => bcrypt($input['password']),
            'email' => $input['email']
        ]);
        $user->assignRole($input['role']);
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
            'role', 'username', 'first_name', 'last_name', 'email'
        ]);

        // find the user based on the parameter id
        $check_user_exist_email = User::where('email', $input['email'])->first();
        if (isset($check_user_exist_email)) return '2';

        $user = User::find($id);
        $user->update([
            'firstname' => $input['first_name'],
            'lastname' => $input['last_name'],
            'email' => $input['email']
        ]);
        $user->syncRoles([ $input['role'] ]);

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
}
