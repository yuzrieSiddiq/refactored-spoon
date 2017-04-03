<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            [
                'quiz_id'       => '1',
                'question'      => 'What is my name?',
                'answer_type'   => 'mcq',
                'answer1'       => 'Yuzrie',
                'answer2'       => 'Brian',
                'answer3'       => 'Bryan',
                'answer4'       => 'Yit Yung',
                'answer5'       => '',
                'correct_answer'=> 'Yuzrie',
            ],
        ]);
    }
}
