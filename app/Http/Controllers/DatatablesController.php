<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use DB;
use App\User;

class DatatablesController extends Controller
{
    /**
     * Process datatables ajax request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    #1 users
    public function getUsersDatatable()
    {
        return Datatables::of(User::select('id', 'firstname', 'lastname', 'email'))->make();

        // TODO: after populate, uncomment these lines
        // return Datatables::of(
        //     User::select('users.id', 'users.username', 'users.email', 'roles.name')
        //     ->leftJoin('user_has_roles', 'user_has_roles.user_id', '=', 'users.id')
        //     ->leftJoin('roles', 'user_has_roles.role_id', '=', 'roles.id')
        // )->make();
    }
}
