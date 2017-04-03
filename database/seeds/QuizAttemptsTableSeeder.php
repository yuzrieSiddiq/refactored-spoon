<?php

use Illuminate\Database\Seeder;

class QuizAttemptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quiz_attempts')->insert([
            [
                'user_id'   => '3',
                'quiz_id'   => '1',
                'is_attempted' => false,
            ],[
                'user_id'   => '3',
                'quiz_id'   => '2',
                'is_attempted' => false,
            ]
        ]);
    }
}
