<?php

use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            [
                'user_id'   => '3',
                'unit_id'   => '1',
                'semester'  => 'S1',
                'year'      => 2017,
                'team_number' => 1,
                'is_group_leader' => true,
            ],
        ]);
    }
}
