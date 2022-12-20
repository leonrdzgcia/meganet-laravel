<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $fieldForLogin = 'email';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
        $credentials = $request->only('email', 'password');

        if(isset($credentials['email']) && !filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            $this->fieldForLogin = 'login_user';
        }
//
//        if (Auth::attempt([
//            'email'
//        ])) {
//           dd('ok');
//        }
//        dd('bad');
    }

    public function showLoginForm()
    {
        return view('meganet.auth.login');
    }

    public function username()
    {
        return $this->fieldForLogin;
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        $crendentials = $request->only('email', 'password');
        if (!isset($crendentials[$this->fieldForLogin])){
            $crendentials[$this->fieldForLogin] = $crendentials['email'];
            unset($crendentials['email']);
        }
        return $crendentials;
    }
}
