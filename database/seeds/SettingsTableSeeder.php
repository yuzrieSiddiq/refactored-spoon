<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'name'=> 'semester',
                'value'  => 'S1'
            ],[
                'name'=> 'year',
                'value'  => '2017'
            ],
        ]);
    }
}
