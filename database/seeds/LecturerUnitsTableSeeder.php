<?php

use Illuminate\Database\Seeder;

class LecturerUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lecturer_units')->insert([
            [
                'user_id' => '2',
                'unit_id' => '1',
            ],
        ]);
    }
}
