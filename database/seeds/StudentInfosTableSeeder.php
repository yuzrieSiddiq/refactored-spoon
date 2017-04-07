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
            ],[
                'user_id'   => '4',
                'student_id'=> '4301711',
                'locality'  => 'LOCAL'
            ],[
                'user_id'   => '5',
                'student_id'=> '4301712',
                'locality'  => 'LOCAL'
            ],[
                'user_id'   => '6',
                'student_id'=> '4301713',
                'locality'  => 'LOCAL'
            ],
        ]);
    }
}
