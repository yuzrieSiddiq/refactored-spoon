<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Auth;

use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use App\Model\StudentInfo;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request, JWTAuth $JWTAuth)
    {
        $input = $request->only(['username', 'password']);

        // get the StudentInfo (using username)
        $this_student = StudentInfo::with(['user' => function($query) {
            $query->with('roles')->get();
        }])->where('student_id', $input['username'])->first();

        // use this_student to obtain the email and validate with the given password
        $credentials = [];
        $credentials['email'] = $this_student->user->email;
        $credentials['password'] = $input['password'];

        try {
            // attempt with the given $credentials
            $token = $JWTAuth->attempt($credentials);
            $user = Auth::user();   // Auth using overridden setting from $JWTAuth

            // check if a student -> use check here instead of middleware
            if(!$token || !$user->hasRole('Student')) {
                throw new AccessDeniedHttpException();
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        return response()
            ->json([
                'status' => 'ok',
                'token' => $token,
                'user' => $user,
            ]);
    }
}
