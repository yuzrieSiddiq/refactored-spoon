<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\User;
use Spatie\Permission\Model\Role;

class UsersRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_has_roles')->insert([
            [
                // Administrator
                'user_id'       => '1',
                'role_id'       => '1',
            ],[
                // Lecturer
                'user_id'       => '2',
                'role_id'       => '2',
            ],[
                // Student 1
                'user_id'       => '3',
                'role_id'       => '3',
            ],[
                // Student 2
                'user_id'       => '4',
                'role_id'       => '3',
            ],[
                // Student 3
                'user_id'       => '5',
                'role_id'       => '3',
            ],[
                // Student 4
                'user_id'       => '6',
                'role_id'       => '3',
            ],
        ]);

        $assignedroles = DB::table('user_has_roles')->get();
        foreach ($assignedroles as $user_role) {
            User::find($user_role->user_id)->roles()->sync([$user_role->role_id]);
        }
    }
}
