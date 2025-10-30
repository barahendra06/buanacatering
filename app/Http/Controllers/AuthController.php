<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	protected $username = 'username';
	protected $redirectPath = 'sessionSet';
	protected $redirectTo = 'sessionSet';

	/*
	https://laracasts.com/discuss/channels/general-discussion/log-in-with-username-or-email-in-laravel-5
	use AuthenticatesAndRegistersUsers, ThrottlesLogins {
    AuthenticatesAndRegistersUsers::postLogin as laravelPostLogin;
	}

	public function postLogin(Request $request) {
		$field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$request->merge([$field => $request->input('login')]);
		$this->username = $field;

		return self::laravelPostLogin($request);
	}
	*/

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:20',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
