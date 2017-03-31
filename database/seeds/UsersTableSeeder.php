<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'      => 'student',
                'email'     => 'student@email.com',
                'password'  => bcrypt('123456')
            ],[
                'name'      => 'lecturer',
                'email'     => 'lecturer@email.com',
                'password'  => bcrypt('123456')
            ],[
                'name'      => 'admin',
                'email'     => 'admin@email.com',
                'password'  => bcrypt('123456')
            ]
        ]);
    }
}
