<?php

use Illuminate\Database\Seeder;

class UnitContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_contents')->insert([
            [
                'unit_id'   => '1',
                'content'   => 'test content 1'
            ],
        ]);
    }
}
