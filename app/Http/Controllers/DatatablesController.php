<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use DB;
use App\User;
use App\Model\Unit;
use App\Model\Quiz;
use App\Model\Question;
use App\Model\Student;
use App\Model\LecturerUnit;
use App\Model\Settings;
use App\Model\Group;

class DatatablesController extends Controller
{
    /**
     * Process datatables ajax request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // users index
    public function getUsersDatatable()
    {
        return Datatables::of(
            User::select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'roles.name')
            ->leftJoin('user_has_roles', 'user_has_roles.user_id', '=', 'users.id')
            ->leftJoin('roles', 'user_has_roles.role_id', '=', 'roles.id')
        )->make();
    }

    // users index
    public function getStudentsDatatable($unit_id)
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        return Datatables::of(
            Student::select('students.id', 'student_infos.student_id', 'users.firstname', 'users.lastname',
                'students.team_number', 'students.is_group_leader', 'students.group_number')
            ->leftJoin('users', 'users.id', '=', 'students.user_id')
            ->leftJoin('units', 'units.id', '=', 'students.unit_id')
            ->leftJoin('student_infos', 'student_infos.user_id', '=', 'students.user_id')
            ->where('units.id', $unit_id)
            ->where('students.semester', $semester)
            ->where('students.year', $year)
        )->make();
    }

    // units index
    public function getUnitsDatatable()
    {
        return Datatables::of(Unit::select('id', 'code', 'name'))->make();
    }

    // lecturer_units index
    public function getLUnitsDatatable()
    {
        $semester = Settings::where('name', 'semester')->first()->value;
        $year = Settings::where('name', 'year')->first()->value;

        return Datatables::of(
            LecturerUnit::select('lecturer_units.id', 'units.code', 'users.firstname', 'users.lastname')
            ->leftJoin('users', 'users.id', '=', 'lecturer_units.user_id')
            ->leftJoin('units', 'units.id', '=', 'lecturer_units.unit_id')
            ->where('semester', $semester)
            ->where('year', $year)
        )->make();
    }

    // quiz index
    public function getQuizzesDatatable()
    {
        // TODO: filter by Unit of current lecturer --> lecturer_units table
        return Datatables::of(Quiz::select('id', 'title', 'type', 'status'))->make();
    }

    // question index
    public function getQuestionsDatatable($quiz_id)
    {
        return Datatables::of(
            Question::select('id', 'quiz_id', 'question', 'correct_answer')
            ->where('quiz_id', $quiz_id)
        )->make();
    }

    public function getGroupQuestionsDatatable($quiz_id, $group_no)
    {
        return Datatables::of(
            Group::select('questions.id', 'groups.chosen_questions', 'questions.question', 'questions.correct_answer')
            ->leftJoin('questions', 'questions.quiz_id', '=', 'groups.quiz_id')
            ->where('groups.group_number', $group_no)
            ->where('groups.quiz_id', $quiz_id)
        )->make();
    }
}
