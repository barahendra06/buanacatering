<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Province;
use Carbon\Carbon;
use App\Member;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use DB;

use Auth;
use Socialite;

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

    protected $redirectPath = '/member/dashboard';
    protected $redirectTo = '/member/dashboard';
    
    public function getCredentials(Request $request)
    {
        $credentials = $request->only($this->loginUsername(), 'password');
        $credentials = array_add($credentials, 'confirmed', '1');
        $credentials = array_add($credentials, 'expired', '0');     
        return $credentials;
    }

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $yearMax = intval(date("Y"))-MINIMUM_AGE;
        $yearMin = intval(date("Y"))-MAXIMUM_AGE;
        $month = intval(date("m"));
        $day = intval(date("d"));
        $dateMin = strtotime($yearMin.'-'.$month.'-'.$day);
        $dateMax = strtotime($yearMax.'-'.$month.'-'.$day);
        $dateMin1 =  date('Y-m-d', $dateMin);
        $dateMax1 =  date('Y-m-d', $dateMax);
        // dd($dateMin1.' '.$dateMax1);
        return Validator::make($data, [
            //'username' => 'required|max:20',
            'name' => 'required|max:80',
            'email' => 'required|email|max:255|unique:user',
            'password' => 'required|confirmed|min:6',
            'birth_date' => 'required|date|after:'.$dateMin1.'|before:'.$dateMax1,
            'school' => 'required',
            'education' => 'required',
            'gender' => 'required',
            'handphone' => 'required',
            'province' => 'required',
            'address' => 'required',
            'city' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User();   
        try 
        {
            DB::transaction(function () use($data, &$user) {   



                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                if($data['reference_id']=="")
                {
                    $user->reference_id = NULL;
                }
                else
                {
                    $referenceUser = User::find($data['reference_id']);  //check if the reference user is valid
                    if($referenceUser)
                    {
                        $user->reference_id = $data['reference_id'];
                    }
                }
                $user->role_id = 1;//user
                $user->save();
                $user->activation_code = $user->id . str_random(30);
                $user->save();

                //create employee data here
                $member = new Member();
                $member->user_id = $user->id;
                $member->name = $data['name'];
                
                if($data['province']=='' or $data['province']==null){
                    $member->province_id = 2;    
                }else{
                    $member->province_id = $data['province'];
                }
                
                // $member->province_id = $data['province'];
                $member->mobile_phone = $data['handphone'];
                $member->gender = $data['gender'];
                if($data['education']=='' or $data['education']==null){
                    $member->education_type_id = 2;    
                }else{
                    $member->education_type_id = $data['education'];
                }
                

                $member->school = $data['school'];
                $time = strtotime($data['birth_date']);
                $newformat = date('Y-m-d',$time);
                $member->birth_date = $newformat;
                $member->address = $data['address'];
                $member->city = $data['city'];
                // $member->phone = $data['phone'];
                $member->save();
                // dd($member);
                
            });         
        }
        catch(Exception $e) 
        {
            ;
        }
			
		//send verification mail to user
		//--------------------------------------------------------------------------------------------------------------
		 $data['confirmation_code'] = $user->activation_code;
		 Mail::queue('emails.welcome', $data, function ($message) use ($data) {
			 $message->from('support@mg.user.com', "user");
			 $message->replyTo('support@mg.user.com', "user");
			 $message->subject("Welcome to user.com!");
			 $message->to($data['email'], $data['name']);
			 //$message->to('alief.mf@jawapos.co.id', 'alief maksum');
		 });

		return $user;			
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->create($request->all());
        return redirect()->route('home')
                         ->withMessagepopup('success')
                         ->withName($request->name)
                         ->withEmail($request->email);
    }

    public function getRegister()
    {
        return view('auth.register')->withProvince(Province::orderBy('name', 'asc')->get());
    }


    protected function authenticated(Request $request, User $user){
        $successmessage = "Hey, you've been successfully logged in!";
        $request->session()->flash('success', $successmessage);
        
        $user->last_login = new Carbon;
        $user->save();

        if($request->intended != '')
	    {
            return redirect($request->intended);
        }
        return redirect()->intended($this->redirectPath());
    }

    // ajax message success pop up
    public function registerSuccess(Request $request){
        return view('auth.successRegister')->withName($request->name)
                                           ->withEmail($request->email);
    }


}
