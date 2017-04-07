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
                // Student 1
                'firstname' => 'first name 1',
                'lastname'  => 'last name 1',
                'email'     => '4301710@students.swinburne.edu.my',
                'password'  => bcrypt('123')
            ],[
                // Student 2
                'firstname' => 'first name 2',
                'lastname'  => 'last name 2',
                'email'     => '4301711@students.swinburne.edu.my',
                'password'  => bcrypt('123')
            ],[
                // Student 3
                'firstname' => 'first name 3',
                'lastname'  => 'last name 3',
                'email'     => '4301712@students.swinburne.edu.my',
                'password'  => bcrypt('123')
            ],[
                // Student 4
                'firstname' => 'first name 4',
                'lastname'  => 'last name 4',
                'email'     => '4301713@students.swinburne.edu.my',
                'password'  => bcrypt('123')
            ]
        ]);
    }
}
