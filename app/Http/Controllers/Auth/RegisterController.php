<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\Province;
use Carbon\Carbon;
use App\Member;
use Illuminate\Support\Facades\Mail;
use Validator;
use Auth;
use App\Mail\RegisterUser;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
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
        $this->middleware('guest');
        $this->middleware('check.profile');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $yearMax = intval(date("Y"))-MINIMUM_AGE;
        $yearMin = intval(date("Y"))-MAXIMUM_AGE;
        $month = intval(date("m"));
        $day = intval(date("d"));
        $dateMin = Carbon::createFromFormat('Y-m-d', $yearMin.'-'.$month.'-'.($day));
        $dateMax = Carbon::createFromFormat('Y-m-d', $yearMax.'-'.$month.'-'.($day));
        $dateMin1 =  $dateMin->format('Y-m-d');
        $dateMax1 =  $dateMax->addDay()->format('Y-m-d');
        //dd($dateMin1.' '.$dateMax1);
        return Validator::make($data, [
            //'username' => 'required|max:20',
            'name' => 'required|max:80',
            'email' => 'required|email|max:255|unique:user',
            'password' => 'required|confirmed|min:6',
            // 'birth_date' => 'required|date|after:'.$dateMin1.'|before:'.$dateMax1,
            // 'gender' => 'required',
            // 'school' => 'required',
            // 'handphone' => 'required',
            // 'city' => 'required',
            // 'province' => 'required',
            'terms' => 'required',
        ]);
    }
    
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
	    return redirect()->route('home')
                        ->withErrors($validator);
        }

        $this->create($request->all());
        return redirect()->route('home')
                         ->withMessagepopup('success')
                         ->withName($request->name)
                         ->withEmail($request->email);

    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
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

                if(!isset($data['reference_id']))
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
                $user->role_id = 1;//member
                $user->save();
                $user->activation_code = $user->id . str_random(30);
                $user->save();

                //create employee data here
                $member = new Member();
                $member->user_id = $user->id;
                $member->name = $data['name'];
                
                $member->province_id = isset($data['province']) ? $data['province'] : null;
                $member->mobile_phone = isset($data['handphone']) ? $data['handphone'] : null;
                $member->gender = isset($data['gender']) ? $data['gender'] : null;
                $member->education_type_id = isset($data['education']) ? $data['education'] : null;
                $member->school = isset($data['school']) ? $data['school'] : null;

                if(isset($data['birth_date'])){
                    $time = strtotime($data['birth_date']);
                    $newformat = date('Y-m-d',$time);
                    $member->birth_date = $newformat;
                }else{
                    $member->birth_date = null;
                }
                $member->address = isset($data['address']) ? $data['address'] : null;
                $member->city = isset($data['city']) ? $data['city'] : null;

                $member->save();
                
            });         
        }
        catch(Exception $e) 
        {
            ;
        }
            
        // send verification mail to user
        $data['confirmation_code'] = $user->activation_code;
        Mail::to($data['email'], $data['name'])->send(new RegisterUser($data));

        return $user;           
    }

    // ajax message success pop up
    public function registerSuccess(Request $request){
        return view('auth.successRegister')->withName($request->name)
                                           ->withEmail($request->email);
    }
}
