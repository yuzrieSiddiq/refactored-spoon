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
    public function getStudentsDatatable()
    {
        // TODO: filter by Unit, only show students in the current unit, (e.g: where unit = 'HRM20016')
        return Datatables::of(
            Student::select('students.id', 'users.firstname', 'users.lastname',
                'students.semester', 'students.year', 'students.team_number', 'students.is_group_leader')
            ->leftJoin('users', 'users.id', '=', 'students.user_id')
        )->make();
    }

    // units index
    public function getUnitsDatatable()
    {
        return Datatables::of(Unit::select('id', 'code', 'name'))->make();
    }

    // quiz index
    public function getQuizzesDatatable()
    {
        // TODO: filter by Unit of current lecturer --> lecturer_units table
        return Datatables::of(Quiz::select('id', 'title', 'type', 'status'))->make();
    }

    // question index
    public function getQuestionsDatatable()
    {
        return Datatables::of(
            Question::select('questions.id', 'quizzes.title', 'questions.question', 'questions.answer_type')
            ->leftJoin('quizzes', 'quizzes.id', '=', 'questions.quiz_id')
        )->make();
    }
}