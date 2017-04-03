<?php

use Illuminate\Database\Seeder;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quizzes')->insert([
            [
                'unit_id'=> '1',
                'title'  => 'iRAT1',
                'type'   => 'individual',
                'status' => 'open',
            ],[
                'unit_id'=> '1',
                'title'  => 'tRAT1',
                'type'   => 'group',
                'status' => 'open',
            ]
        ]);
    }
}
