<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = '/member/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->middleware('check.profile', ['except' => 'logout']);
    }

    protected function credentials(Request $request)
    {

        $credentials = $request->only($this->username(), 'password');
        $credentials = array_add($credentials, 'confirmed', '1');
        $credentials = array_add($credentials, 'expired', '0'); 
        return $credentials;
    }


   
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $successmessage = "Hey, you've been successfully logged in!";
        $request->session()->flash('success', $successmessage);
        
        $user->last_login = new Carbon;
        $user->save();

        // redirect to intended url
        if($request->intended)
        {
            return redirect($request->intended);
        }

        // return false to redirect according to $redirectTo variable
        return false;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $user = User::where('email',$request->email)->first();
    	if($user)
    	{
            if($user->expired)
            {
                throw ValidationException::withMessages([
                            $this->username() => ["Thank you for joining with us. Your account has been expired because you are older than 21 years old."],
                        ]);
            }
            if(!$user->confirmed)
            {
                throw ValidationException::withMessages([
                            $this->username() => ["Sorry, your account is almost active. Please activate your account by clicking link on your email. If you don't get activation email, you can click Resend Activation Email on login popup"],
                        ]);
            }
    	}

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
