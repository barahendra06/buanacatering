<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Activity;
use App\ActivityType;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo ="/";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password)
    {
        if($user->expired==0)
        {
            $user->password = bcrypt($password);
            if($user->confirmed==0) 
            {
                $user->confirmed = 1;
                $user->activation_code = null;
                $user->save();
            }
            else
            {
                $user->save();
            }
            

            $this->guard()->login($user);
        }
        else
        {
            return redirect()->back()->withMessage('Sorry, your account had expired.');
        }
    }

    /*public function showResetForm(Request $request, $token = null)
    {
        
        if (is_null($token)) {
            return $this->getEmail();
        }

        if (property_exists($this, 'resetView')) { 
            $passwordReset = DB::table('password_resets')->select('email')->where('token', $token)->first();
            
            if($passwordReset and $passwordReset->email)
            {
                $email = $passwordReset->email;
                return view($this->resetView)->with(compact('token', 'email'));
            }
            else
            {
                return redirect()->route('home')->withMessage("Sorry this link has been expired. Please request forget password again.");
            }
        }

        if (view()->exists('auth.passwords.reset')) {
            $passwordReset = DB::table('password_resets')->select('email')->where('token', $token)->first();
            
            if($passwordReset and $passwordReset->email)
            {
                $email = $passwordReset->email;
                return view('auth.passwords.reset')->with(compact('token', 'email'));
            }
            else
            {
                return redirect()->route('home')->withMessage("Sorry this link has been expired. Please request forget password again.");
            }
        }

        $passwordReset = DB::table('password_resets')->select('email')->where('token', $token)->first();
        if($passwordReset and $passwordReset->email)
        {

            $email = $passwordReset->email;
            return view('auth.reset')->with(compact('token', 'email'));
        }
        else
        {
            return redirect()->route('home')->withMessage("Sorry this link has been expired. Please request forget password again.");
        }
    }*/

}
