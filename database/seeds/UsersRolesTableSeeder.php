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
                // Student
                'user_id'       => '3',
                'role_id'       => '3',
            ],
        ]);

        $assignedroles = DB::table('user_has_roles')->get();
        foreach ($assignedroles as $user_role) {
            User::find($user_role->user_id)->roles()->sync([$user_role->role_id]);
        }
    }
}
