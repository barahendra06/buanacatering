<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\User;
use App\Member;
use App\Activity;
use App\ActivityType;
use Auth;
use DB;

class PasswordController extends Controller
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
	protected $redirectTo = '/member/dashboard';
	
    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
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
                        
                //add registration point to the user
                $activity = new Activity();
                $activity->member_id = $user->member->id;
                $activity->activity_type_id = ACTIVITY_REGISTER;//id activity_type that refer to register
                $activity->content_id = null;
                $activity->poin = ActivityType::where('id',ACTIVITY_REGISTER)->first()->poin;
                $activity->save();

                //add point to the reference member
                if($user->reference_id)
                {
                    $activity = new Activity();
                    $activity->member_id = User::where('id',$user->reference_id)->first()->member->id;
                    $activity->activity_type_id = ACTIVITY_INVITE_MEMBER;//id activity_type that refer to register
                    $activity->content_id = null;
                    $activity->poin = ActivityType::where('id',ACTIVITY_INVITE_MEMBER)->first()->poin;
                    $activity->save();
                }
            }
            else
            {
                
                $user->save();
            }
            

            Auth::guard($this->getGuard())->login($user);
        }
        else
        {
            return redirect()->back()->withMessage('Sorry, your account had expired.');
        }
    }

    public function showResetForm(Request $request, $token = null)
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
                abort('404');
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
                abort('404');
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
            abort('404');
        }
    }

}
