<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
                'unit_id'   => '1',
                'semester'  => 'S1',
                'year'   => 2017,
                'title'  => 'Test 1',
                'type'   => 'individual',
                'status' => 'open',
            ],[
                'unit_id'=> '1',
                'semester'  => 'S1',
                'year'   => 2017,
                'title'  => 'Test 1',
                'type'   => 'group',
                'status' => 'open',
            ],[
                'unit_id'=> '1',
                'semester'  => 'S1',
                'year'   => 2017,
                'title'  => 'Test 2',
                'type'   => 'individual',
                'status' => 'open',
            ],[
                'unit_id'=> '1',
                'semester'  => 'S1',
                'year'   => 2017,
                'title'  => 'Test 2',
                'type'   => 'group',
                'status' => 'close',
            ],
        ]);
    }
}
