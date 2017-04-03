<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersRolesTableSeeder::class);
        $this->call(RolesPermissionsTableSeeder::class);

        $this->call(UnitsTableSeeder::class);
        $this->call(StudentInfosTableSeeder::class);
        $this->call(LecturerUnitsTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(QuizzesTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(QuizAttemptsTableSeeder::class);
    }
}
