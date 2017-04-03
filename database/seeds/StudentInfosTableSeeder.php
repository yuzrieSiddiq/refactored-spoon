<?php

use Illuminate\Database\Seeder;

class StudentInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_infos')->insert([
            [
                'user_id'   => '3',
                'student_id'=> '4301710',
                'locality'  => 'LOCAL'
            ],
        ]);
    }
}
