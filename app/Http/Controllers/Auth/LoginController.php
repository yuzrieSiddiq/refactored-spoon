<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\HTTP\Request;
use Auth;

use QRCode;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->hasRole('Student')) {
            return 'logged in';
        }
    }

    public function logout(Request $request)
    {
        if (Auth::user()->hasRole('Student')) {
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();

            return 'logged out';
        } else if (Auth::user()->hasRole('Lecturer')) {
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();

            return redirect('/');
        }
    }
}
