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
                // Administrator
                'firstname' => 'admin first name',
                'lastname'  => 'admin last name',
                'email'     => 'admin@email.com',
                'password'  => bcrypt('123456')
            ],[
                // Lecturer
                'firstname' => 'first name 1',
                'lastname'  => 'last name 1',
                'email'     => 'testing1@swinburne.edu.my',
                'password'  => bcrypt('123')
            ],[
                // Student
                'firstname' => 'first name 1',
                'lastname'  => 'last name 1',
                'email'     => '4301710@students.swinburne.edu.my',
                'password'  => bcrypt('123')
            ]
        ]);
    }
}
