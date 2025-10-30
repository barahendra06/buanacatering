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
use Response;
use Socialite;
use JWTAuth;
use JWTFactory;

class AuthApiController extends Controller
{
   
    public function getCredentials(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials = array_add($credentials, 'confirmed', '1');
        $credentials = array_add($credentials, 'expired', '0');     
        return $credentials;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($data)
    {
        //email validation
        $existUser = User::where('email',$data['email'])->first();
        if($existUser){
            return "Email already exist";
        }

        //birthdate validation
        $yearMax = intval(date("Y"))-MINIMUM_AGE;
        $yearMin = intval(date("Y"))-MAXIMUM_AGE;
        $month = intval(date("m"));
        $day = intval(date("d"));
        $dateMin1 =  Carbon::createFromFormat('d-m-Y', $day.'-'.$month.'-'.$yearMin);
        $dateMax1 =  Carbon::createFromFormat('d-m-Y', $day.'-'.$month.'-'.$yearMax);
        $checkBirthDate = Carbon::createFromFormat('d-m-Y', $data['birthdate']);
        //check birthdate wheter between datemin and datemax
        if(! $checkBirthDate->between($dateMin1, $dateMax1)){    
            return "Birth date must be between ".$dateMin1->format('d M Y')." and ".$dateMax1->format('d M Y');
        }

        return "success";

        
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create($data)
    {
        $user = new User();   
        $member = new Member();     
        $message = "";
        try 
        {
            DB::transaction(function () use($data, $message, &$user,&$member) {   


                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                if($data["reference_id"]=="")
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
                // $user->save();
                $user->activation_code = $user->id . str_random(30);
                // $user->save();

                //create member data here
                $member->user_id = $user->id;
                $member->name = $data['name'];
                
                if($data['province'] =='' or $data['province']==null){
                    $member->province_id = 2;    
                }else{
                    $member->province_id = $data['province'];
                }

                $member->mobile_phone = $data['handphone'];
                $member->gender = $data['gender'];
                if($data['education_type']=='' or $data['education_type']==null){
                    $member->education_type_id = 2;    
                }else{
                    $member->education_type_id = $data['education_type'];
                }
                

                $member->school = $data['school'];
                $time = strtotime($data['birthdate']);
                $newformat = date('Y-m-d',$time);
                $member->birth_date = $newformat;
                $member->address = $data['address'];
                $member->city = $data['city'];
                // $member->save();
                
            });         
        }
        catch(Exception $e) 
        {
            ;
        }
			
		//send verification mail to user
		//--------------------------------------------------------------------------------------------------------------
		 // $data['confirmation_code'] = $user->activation_code;
		 // Mail::queue('emails.welcome', $data, function ($message) use ($data) {
			//  $message->from('support@mg.user.com', "user");
			//  $message->replyTo('support@mg.user.com', "user");
			//  $message->subject("Welcome to user.com!");
			//  $message->to($data['email'], $data['name']);
			//  //$message->to('alief.mf@jawapos.co.id', 'alief maksum');
		 // });

		return $member;			
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        
        // $arrayCoba = array(
        //             'reference_id'=>$request->reference_id, 
        //             'name' =>$request->name,
        //             'email' =>$request->email,
        //             'password'=>$request->password,
        //             'password_confirm'=>$request->password_confirm,
        //             'education_type'=>$request->education_type,
        //             'school'=>$request->school,
        //             'birthdate'=>$request->birthdate,
        //             'gender'=>$request->gender,
        //             'handphone'=>$request->handphone,
        //             'province'=>$request->province,
        //             'city'=>$request->city,
        //             'address'=>$request->address
        //         );

        $validation = $this->validator($request);
        //if validation success
        if($validation == "success")
        {
            // $user = $this->create($request); 
            $member = $this->create($request);    
            return response()->json(array(
                                    // 'currentUser'=>$user,
                                    'member'=>$member,
                                    'message'=>$validation,
                                    'status_code'=>200,
                                    'error'=>false,
                                    )); 
        }

        //if validation doesn't success
        else
        {
            return response()->json(array(
                                    'message'=>$validation,
                                    'status_code'=>200,
                                    'error'=>true,
                                    ));   
        }
        // return response()->json(array(
        //                             'arrayCoba'=>$arrayCoba,
        //                             'message'=>$request->password,
        //                             'status_code'=>200,
        //                             'error'=>false,
        //                             ));
    }

    public function getRegister()
    {
        $provinces = DB::table('province')->select('id', 'name')->orderBy('name', 'asc')->get();

        return response()->json(array(
                                    'provinces'=>$provinces,
                                    'status_code'=>200,
                                    'error'=>false,
                                    ));
    }

    public function postLogin(Request $request)
    {
        // grab credentials from request
        $credentials = $this->getCredentials($request);
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                                            'error' => 'true',
                                            'status_code'=> 401,
                                            'message'=>'Invalid credentials'
                                        ]);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                                        'error' => 'true',
                                        'status_code'=> 500,
                                        'message'=>'Error when logging in. Please Try again.'
                                    ]);
        }
        $currentUser = array(
                    'id' =>auth()->user()->id ,
                    'email' =>auth()->user()->email,
                    'member' =>auth()->user()->member->toArray(),
                    'token'=>$token
                );

        
        // all good so return the token
        return response()->json(array(
                                    'currentUser'=>$currentUser,
                                    'status_code'=>200,
                                    'error'=>false,
                                    'message'=>'Success to login'
                                    ));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user()->token();
        $user->revoke();

        return response()->json([
            'message' => 'Successfully logout',
            'data' => null,
        ]);
    }
}
