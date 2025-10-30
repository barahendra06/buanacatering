<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Province;
use Carbon\Carbon;
use App\Member;
use App\Activity;
use App\ActivityType;
use Illuminate\Support\Facades\Mail;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use DB;

use Auth;
use Socialite;

class AuthSocialiteController extends Controller
{


    use RegistersUsers, ThrottlesLogins;

    public function __construct()
    {
        $this->middleware('check.profile');
    }
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
    	return Socialite::driver('facebook')->fields([
            'first_name', 'last_name','middle_name', 'email', 'gender', 'public_profile','birthday'
        ])->scopes([
            'email', 'public_profile', 'user_birthday'
        ])->redirect();
    }
 
    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->fields([
                'first_name', 'last_name','middle_name', 'email', 'gender','birthday'
            ])->user();
        } catch (Exception $e) {
            return redirect()->route('facebook-auth');
        }

        //if facebook account has email
        if($facebookUser->email)
        {
            $authUser = User::where('email', $facebookUser->getEmail())->first();

            //if email has found, so make user log in
            if($authUser){
                if(!$authUser->facebook_id)//facebook_id is null, so fill it
                {
                    $authUser->facebook_id = $facebookUser->id;
                    $authUser->save();
                }
                if($authUser->confirmed==0)//if account is found and account have not confirm yet
                {
                    $authUser->confirmed = 1;
                    $authUser->confirmed_at = Carbon::now();
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('email not found');    
        }

        //if facebook_id exist in database
        if($facebookUser->id)
        {
            $authUser = User::where('facebook_id', $facebookUser->id)->first();
            //if facebook_id has found, so make user log in
            if($authUser){
                if(isset($facebookUser->user['email']))//email is null and facebook account has new email, so fill it
                {
                    $authUser->email = $facebookUser->user['email'];
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('facebook_id not found');    
        }
        
        // dd('no email and facebook_id not found');

        if(isset($facebookUser->user['birthday']))
        {
            //check age
            $birthday = Carbon::createFromFormat('m/d/Y', $facebookUser->user['birthday']);
            
            $from = (new Carbon('now'))->hour(0)->minute(0)->second(0)->subYears(MAXIMUM_AGE);
            $to = (new Carbon('now'))->hour(0)->minute(0)->second(0)->subYears(MINIMUM_AGE);

            // dd($birthday->format('d/m/Y').", from=".$from->format('d/m/Y')." to=".$to->format('d/m/Y'));

            if($from->lt($birthday) and $to->gt($birthday))
            {
                $data['facebookUser'] = $facebookUser;
                
                return view('auth.registerFacebook',$data);
            }
            else
            {
                return view('auth.login')->withError('Sorry, your age is not in our range ('.MINIMUM_AGE.' - '.(MAXIMUM_AGE-1).'), you can not join to user.');
            }    
        }
        else
        {

            $data['facebookUser'] = $facebookUser;
            return view('auth.registerFacebook',$data);
        }
        
    }
 
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function create($facebookUser)
    {
        $user = new User();
        try 
        {
            DB::transaction(function () use($facebookUser, &$user) {   
                if(isset($facebookUser['email']))
                {
                    $user->email = $facebookUser['email'];    
                }
                else
                {
                    $user->email = null;       
                }
                $user->facebook_id = $facebookUser['facebook_id'];
                $user->role_id = 1;//user
                $user->password = "";
                $user->last_login = Carbon::now();
                $user->confirmed = 1;
                $user->confirmed_at = Carbon::now();
                $user->activation_code = null;
                if($facebookUser['reference_id']=="")
                {
                    $user->reference_id = NULL;
                }
                else
                {
                    $referenceUser = User::find($facebookUser['reference_id']);  //check if the reference user is valid
                    if($referenceUser)
                    {
                        $user->reference_id = $facebookUser['reference_id'];
                    }
                }
                $user->save();

                //create employee data here
                $member = new Member();
                $member->user_id = $user->id;
                $member->name = $facebookUser['name'];
                $member->province_id = $facebookUser['province'];    //set default to jawa barat
                $member->mobile_phone = $facebookUser['handphone'];  //set default to 0
                $member->gender = $facebookUser['gender'];
                $member->education_type_id = $facebookUser['education'];     // set default educatio to SMA
                $member->school = $facebookUser['school'];

                $time = strtotime($facebookUser['birth_date']);
                $newformat = date('Y-m-d',$time);
                $member->birth_date = $newformat;

                $member->address = $facebookUser['address'];
                $member->city = $facebookUser['city'];
                $member->save();

                $folderPath = 'uploads/' . $member->id . '/';
                $member->avatar_path = $folderPath . uniqid() . '.jpg'; // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);
                Image::make($facebookUser['avatar'])->save($member->avatar_path);
                $member->save();

                //add registration point to the user
                $activity = new Activity();
                $activity->member_id = $member->id;
                $activity->activity_type_id = ACTIVITY_REGISTER;//id activity_type that refer to register
                $activity->content_id = null;
                $activity->poin = ActivityType::where('id',ACTIVITY_REGISTER)->first()->poin;
                $activity->save();


            });         
        }
        catch(Exception $e) 
        {
            return view('auth.login')->withError('Sorry, error when proccessing. Create member again');
        }
        return $user;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function registerFacebook(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $user = $this->create($request->all());

        auth()->login($user);
        return redirect()->route('member-dashboard'); 
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
            'facebook_id' => 'required|unique:user',
            'name' => 'required|max:80',
            'birth_date' => 'required|date|after:'.$dateMin1.'|before:'.$dateMax1,
        ]);
    }

    // ****************** GOOGLE OAUTH2 *******************
    // first, redirect to google to show consent screen 
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes([
                                                'https://www.googleapis.com/auth/user.birthday.read',
                                                'https://www.googleapis.com/auth/userinfo.email',
                                                'https://www.googleapis.com/auth/userinfo.profile',
                                                'https://www.googleapis.com/auth/plus.login',
                                                'https://www.googleapis.com/auth/plus.me'
                                            ])->redirect();
    }

    // exchange authorization code (result from first step) with access token and refresh token
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                                    ->scopes([
                                                'https://www.googleapis.com/auth/user.birthday.read',
                                                'https://www.googleapis.com/auth/userinfo.email',
                                                'https://www.googleapis.com/auth/userinfo.profile',
                                                'https://www.googleapis.com/auth/plus.login',
                                                'https://www.googleapis.com/auth/plus.me'
                                            ])->user();

            $http = new \GuzzleHttp\Client([
                                                'headers' => [
                                                                "Content-Type"=>"application/json",
                                                                "Authorization"=>"Bearer ".$googleUser->token
                                                             ]
                                            ]);
    	    $requestMask = 'requestMask.includeField=person.addresses,person.age_ranges,person.birthdays,person.email_addresses,person.genders,person.names,person.nicknames,person.phone_numbers,person.photos';
	    
            $response = $http->get('https://people.googleapis.com/v1/people/me?'.$requestMask);
            $responseArray = json_decode((string) $response->getBody(), true);
	        $birthdayArray = array_pop($responseArray['birthdays']);
	    
            $googleUser->user['birthday'] = $birthdayArray['date']['month'].'/'.
	                                    $birthdayArray['date']['day'].'/'.
					    $birthdayArray['date']['year'];

        } catch (\Exception $e) {
            // return response()->json(['message' => class_basename( $e ) . ' in ' . basename( $e->getFile() ) . ' line ' . $e->getLine() . ': ' . $e->getMessage()], 500);
            return redirect()->route('home')->withMessage("Sorry, something wrong with our connection and google. Please try again later.");
        }


        //if google account has email
        if($googleUser->email)
        {
            $authUser = User::where('email', $googleUser->email)->first();
	    
            //if email has found, so make user log in
            if($authUser){
                if(!$authUser->google_id)//google_id is null, so fill it
                {
                    $authUser->google_id = $googleUser->id;
                    $authUser->save();
                }
                if($authUser->confirmed==0)//if account is found and account have not confirm yet
                {
                    $authUser->confirmed = 1;
                    $authUser->confirmed_at = Carbon::now();
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('email not found');    
        }

        //if google_id exist in database
        if($googleUser->id)
        {
            $authUser = User::where('google_id', $googleUser->id)->first();

            //if google_id has found, so make user log in
            if($authUser){
                if(isset($googleUser->user['email']))//email is null and facebook account has new email, so fill it
                {
                    $authUser->email = $googleUser->user['email'];
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('google_id not found');    
        }
        
        // dd('no email and google_id not found');

        //check age
        $birthday = Carbon::createFromFormat('m/d/Y', $googleUser->user['birthday']);

        // create user and member using less data
        try 
        {
            $user = new User();
            DB::transaction(function () use($googleUser, &$user, $birthday) {   
                
                $user->email = $googleUser->email ? $googleUser->email : null;       
                $user->google_id = $googleUser->id;
                $user->role_id = 1;//user
                $user->password = "";
                $user->last_login = new Carbon;
                $user->confirmed = 1;
                $user->confirmed_at = Carbon::now();
                $user->activation_code = null;
                $user->save();

                //create employee data here
                $member = new Member();
                $member->user_id = $user->id;
                $member->name = $googleUser->name ? $googleUser->name : null;
                $member->province_id = null;    //set default to jawa barat
                $member->mobile_phone = null;  //set default to 0
                $member->gender = isset($googleUser->user['gender']) ? $googleUser->user['gender'] : 'male'; // default value is male
                $member->education_type_id = null;     // set default educatio to SMA
                $member->school = null;

                $member->birth_date = $birthday->format('Y-m-d');

                $member->address = null;
                $member->city = null;
                $member->save();

                $folderPath = 'uploads/' . $member->id . '/';
                $member->avatar_path = $folderPath . uniqid() . '.jpg'; // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);
                Image::make($googleUser->avatar)->save($member->avatar_path);
                $member->save();

                //add registration point to the user
                $activity = new Activity();
                $activity->member_id = $member->id;
                $activity->activity_type_id = ACTIVITY_REGISTER;//id activity_type that refer to register
                $activity->content_id = null;
                $activity->poin = ActivityType::where('id',ACTIVITY_REGISTER)->first()->poin;
                $activity->save();


            });         
        }
        catch(\Exception $e) 
        {
            return view('auth.login')->withError('Sorry, error when proccessing. Create member again');
        }
        
        auth()->login($user);// log user in
        return redirect()->route('member-dashboard');   
        
    }

    // ****************** FACEBOOK OAUTH2 *******************
    // first, redirect to facebook to show consent screen 
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->fields([
            'first_name', 'last_name','middle_name', 'email', 'gender', 'public_profile'
        ])->scopes([
            'email', 'public_profile'
        ])->redirect();
    }

    // exchange authorization code (result from first step) with access token and refresh token
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->fields([
                'first_name', 'last_name','middle_name', 'email', 'gender'
            ])->user();
        } catch (\Exception $e) {
            return redirect()->route('home')->withMessage("Sorry, something wrong when contacting facebook. Please try again later.");
        }
	
        //if google account has email
        if($facebookUser->email)
        {
            $authUser = User::where('email', $facebookUser->email)->first();
        
            //if email has found, so make user log in
            if($authUser){
                if(!$authUser->facebook_id)//facebook_id is null, so fill it
                {
                    $authUser->facebook_id = $facebookUser->id;
                    $authUser->save();
                }
                if($authUser->confirmed==0)//if account is found and account have not confirm yet
                {
                    $authUser->confirmed = 1;
                    $authUser->confirmed_at = Carbon::now();
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('email not found');    
        }

        //if facebook_id exist in database
        if($facebookUser->id)
        {
            $authUser = User::where('facebook_id', $facebookUser->id)->first();

            //if facebook_id has found, so make user log in
            if($authUser){
                if(isset($facebookUser->user['email']))//email is null and facebook account has new email, so fill it
                {
                    $authUser->email = $facebookUser->user['email'];
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('facebook_id not found');    
        }
        
        // create user and member using few data
        try 
        {
            $user = new User();
            DB::transaction(function () use($facebookUser, &$user) {   
                
                $user->email = $facebookUser->email ? $facebookUser->email : null;       
                $user->facebook_id = $facebookUser->id;
                $user->role_id = 1;//user
                $user->password = "";
                $user->last_login = new Carbon;
                $user->confirmed = 1;
                $user->confirmed_at = Carbon::now();
                $user->activation_code = null;
                $user->save();

                //create employee data here
                $member = new Member();
                $member->user_id = $user->id;
                $member->name = ($facebookUser->user['first_name'] ? $facebookUser->user['first_name'] : "Name") ." ". ($facebookUser->user['last_name'] ? $facebookUser->user['last_name'] : "Name") ;
                $member->province_id = null;    //set default to jawa barat
                $member->mobile_phone = null;  //set default to 0
                $member->gender = isset($facebookUser->user['gender']) ? $facebookUser->user['gender'] : 'male'; // default value is male
                $member->education_type_id = null;     // set default educatio to SMA
                $member->school = null;

                $member->birth_date = null;

                $member->address = null;
                $member->city = null;
                $member->save();

                $folderPath = 'uploads/' . $member->id . '/';
                $member->avatar_path = $folderPath . uniqid() . '.jpg'; // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);
                Image::make($facebookUser->avatar)->save($member->avatar_path);
                $member->save();

                //add registration point to the user
                $activity = new Activity();
                $activity->member_id = $member->id;
                $activity->activity_type_id = ACTIVITY_REGISTER;//id activity_type that refer to register
                $activity->content_id = null;
                $activity->poin = ActivityType::where('id',ACTIVITY_REGISTER)->first()->poin;
                $activity->save();


            });         
        }
        catch(Exception $e) 
        {
            return view('auth.login')->withError('Sorry, error when proccessing. Create member again');
        }
        
        auth()->login($user);// log user in
        return redirect()->route('member-dashboard');   
        
    }

    // ****************** TWITTER OAUTH2 *******************
    // first, redirect to twitter to show consent screen 
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    // exchange authorization code (result from first step) with access token and refresh token
    public function handleTwitterCallback()
    {
        try {
            $twitterUser = Socialite::driver('twitter')->user();

        } catch (\Exception $e) {
            // return response()->json(['message' => class_basename( $e ) . ' in ' . basename( $e->getFile() ) . ' line ' . $e->getLine() . ': ' . $e->getMessage()], 500);
            return redirect()->route('home')->withMessage("Sorry, something wrong with our connection and Twitter. Please try again later.");
        }

        //if google account has email
        if($twitterUser->email)
        {
            $authUser = User::where('email', $twitterUser->email)->first();
        
            //if email has found, so make user log in
            if($authUser){
                if(!$authUser->twitter_id)//twitter_id is null, so fill it
                {
                    $authUser->twitter_id = $twitterUser->id;
                    $authUser->save();
                }
                if($authUser->confirmed==0)//if account is found and account have not confirm yet
                {
                    $authUser->confirmed = 1;
                    $authUser->confirmed_at = Carbon::now();
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('email not found');    
        }

        //if twitter_id exist in database
        if($twitterUser->id)
        {
            $authUser = User::where('twitter_id', $twitterUser->id)->first();

            //if twitter_id has found, so make user log in
            if($authUser)
            {
                if(isset($twitterUser->email))//email is null and facebook account has new email, so fill it
                {
                    $authUser->email = $twitterUser->email;
                    $authUser->save();
                }
                auth()->login($authUser);// log user in
                return redirect()->route('member-dashboard');    
            }
        
            // dd('twitter_id not found');    
        }
        
        // dd('no email and twitter_id not found');


        // create user and member using less data
        try 
        {
            $user = new User();
            DB::transaction(function () use($twitterUser, &$user) {   
                
                $user->email = $twitterUser->email ? $twitterUser->email : null;       
                $user->twitter_id = $twitterUser->id;
                $user->role_id = 1;//user
                $user->password = "";
                $user->last_login = new Carbon;
                $user->confirmed = 1;
                $user->confirmed_at = Carbon::now();
                $user->activation_code = null;
                $user->save();

                //create employee data here
                $member = new Member();
                $member->user_id = $user->id;
                $member->name = $twitterUser->name ? $twitterUser->name : null;
                $member->province_id = null;    //set default to jawa barat
                $member->mobile_phone = null;  //set default to 0
                $member->gender = isset($twitterUser->gender) ? $twitterUser->gender : 'male'; // default value is male
                $member->education_type_id = null;     // set default educatio to SMA
                $member->school = null;

                $member->birth_date = null;

                $member->address = null;
                $member->city = null;
                $member->save();

                $folderPath = 'uploads/' . $member->id . '/';
                $member->avatar_path = $folderPath . uniqid() . '.jpg'; // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);
                Image::make($twitterUser->avatar_original)->save($member->avatar_path);
                $member->save();

                //add registration point to the user
                $activity = new Activity();
                $activity->member_id = $member->id;
                $activity->activity_type_id = ACTIVITY_REGISTER;//id activity_type that refer to register
                $activity->content_id = null;
                $activity->poin = ActivityType::where('id',ACTIVITY_REGISTER)->first()->poin;
                $activity->save();


            });         
        }
        catch(\Exception $e) 
        {
            return view('auth.login')->withError('Sorry, error when proccessing. Create member again');
        }
        
        auth()->login($user);// log user in
        return redirect()->route('member-dashboard');   
        
    }
}