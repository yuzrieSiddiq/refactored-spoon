<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            [
                'code'  => 'HRM20016',
                'name'  => 'Introduction to Human Resource Management',
                'description' => '-'
            ],
            [
                'code'  => 'TEST0001',
                'name'  => 'Test Subject 01',
                'description' => 'very fun much wow'
            ],
        ]);
    }
}
