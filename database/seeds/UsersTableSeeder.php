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
                'firstname' => 'MungLing',
                'lastname'  => 'Voon',
                'email'     => 'mvoon@swinburne.edu.my',
                'password'  => bcrypt('abc123')
            ],
        ]);
    }
}
