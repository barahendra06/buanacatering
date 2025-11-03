<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Province;
use App\User;
use App\Activity;
use App\Content;
use App\Notification;
use App\ActivityType;
use App\Member;
use App\RedemptionPrize;
use App\RedemptionTransaction;
use App\Roles;
use App\ProductCategory;
use App\Product;
use App\ProductPackage;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Carbon\Carbon;
use Cache;
use Excel;
use Response;
use Datatables;
use Gate;
use Hash;
use Event;
use App\Events\Image\ImageDeleted;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        //set_time_limit ( 600 );

        //ini_set('memory_limit', '750M');    

        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->with('province')->first();

        $param[''] = '' ;
        $members = Member::with('user','province')
                              ->whereHas('user', function ($query) {
                                  $query->member();
                                });

        if($request->province_id)
        {
            $param['province_id'] = $request->province_id;          
            $members = $members->where('province_id', $request->province_id);
        }   

        if($request->gender)
        {
            $param['gender'] = $request->gender;            
            $members = $members->where('gender', $param['gender']);
        }   
        if($request->join)
        {          
            if($request->join == 'today')
            {
                $param['join'] = $request->join;
                $members = $members->whereDate('created_at', '=', Carbon::today()->toDateString());    
            }
            elseif($request->join == 'all')
            {
                $param['join'] = $request->join; 
            }
            elseif($request->join == 'custom')
            {
                $param['join'] = $request->join;
                if($request->startDate == '')
                {
                    return redirect()->back()->withMessage('Wrong Start Date !!');
                } 
                elseif($request->endDate == '')
                {
                    return redirect()->back()->withMessage('Wrong End Date !!');
                } 
                $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate);
                $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate);
                $param['startDate'] = $startDate;
                $param['endDate'] = $endDate;

                $members = $members->whereBetween('created_at', array($startDate, $endDate)); 

            }
            
        }       
        else
        {
            $param['join'] = 'today';
            $members = $members->whereDate('created_at', '=', Carbon::today()->toDateString());   
        }

        $membersTemp =clone $members;
        
        $summary['total'] = $members->count();
        $members = clone $membersTemp;
        $summary['male'] = $members->where('gender', 'male')->count();
        $members = clone $membersTemp;
        $summary['female'] = $members->where('gender', 'female')->count();

        $members = clone $membersTemp;
        $summary['age1'] = $members->whereDate('birth_date', '>=', Carbon::today()->subYears(15)->toDateString())
                                   ->whereDate('birth_date', '<=', Carbon::today()->subYears(12)->toDateString())->count();   
                                   
        $members = clone $membersTemp;                       
        $summary['age2'] = $members->whereDate('birth_date', '>=', Carbon::today()->subYears(18)->toDateString())
                                   ->whereDate('birth_date', '<=', Carbon::today()->subYears(15)->subDays(1)->toDateString())->count(); 

        $members = clone $membersTemp;                         
        $summary['age3'] = $members->whereDate('birth_date', '>=', Carbon::today()->subYears(MAXIMUM_AGE)->toDateString())
                                   ->whereDate('birth_date', '<=', Carbon::today()->subYears(18)->subDays(1)->toDateString())->count();

        $members = clone $membersTemp;                         
        $summary['age4'] = $members->whereDate('birth_date', '<', Carbon::today()->subYears(MAXIMUM_AGE)->toDateString())->count();
                                   

        $data['title'] = "Member List";   

        $data['param'] = $param;
        $data['summary'] = $summary;
        $data['gender'] = array('male' => 'Male', 'female' => 'Female');
        $data['join'] = array('all'=>'All Time','today'=>'Today','custom'=>'Custom');

        return view('member.index',$data);
            
    }

    /**
     * Display a listing of the resource through ajax.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAjax(Request $request)
    {

        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->with('province')->first();

        $members = DB::table('member')
                         ->leftjoin('user','member.user_id','=','user.id')
                         ->leftjoin('province','member.province_id','=','province.id')
                         ->leftJoin('activity','member.id','=','activity.member_id')
                         ->whereIn('user.role_id',[ROLE_MEMBER, ROLE_ADMIN])
                         ->whereNull('activity.deleted_at')
                         ->groupBy('user.id')
                         ->select(
                                    'user.id as id',
                                    'member.name as name',
                                    'user.email as email',
                                    'user.facebook_id as facebook_id',
                                    'education_type.name as education',
                                    'province.name as province',
                                    'member.city as city',
                                    DB::raw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) as age'),
                                    DB::raw('IF(user.confirmed=0,"Inactive","Active") as status'),
                                    DB::raw('if(sum(activity.poin),sum(activity.poin),0) as poin'),
                                    DB::raw('DATE_FORMAT(member.created_at,"%d-%m-%Y %T") as `join`'),
                                    'member.id as member_id',
                                    'member.gender as gender'
                                )
                         ;
        
        if($request->gender)
        {         
            $members = $members->where('member.gender', $request->gender);
        }          
        if($request->education_type_id)
        {       
            $members = $members->where('member.education_type_id', $request->education_type_id);
        }   

        if($request->province_id)
        {       
            $members = $members->where('member.province_id', $request->province_id);
        } 

        if($request->join)
        {          
            if($request->join == 'today')
            {
                $members = $members->whereDate('member.created_at', '=', Carbon::today()->toDateString());    
            }
            elseif($request->join == 'all')
            {
            }
            elseif($request->join == 'custom')
            {
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->endDate)->addDay();
                $members = $members->whereBetween('member.created_at', array($request->startDate, $endDate))->orderBy('member.created_at');    
            }
            
        }       
        else
        {
            $members = $members->whereDate('member.created_at', '=', Carbon::today()->toDateString());   
        }

        return Datatables::query($members)
                        // ->setTotalRecords($request->total)
                        ->make(true) ;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function summary()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $this->authorize('create', Member::class);

        $data['role_choices'] = Roles::orderBy('role', 'asc')->pluck('role', 'id');
        $data['title'] = "Create New Member";
        return view('member.create', $data);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('store', Member::class);
        
        $validatedData = $request->validate([
            'email' => 'required|unique:user,email|max:191',
        ]);

        try 
        {
            DB::transaction(function () use($request) 
            {
                $user = new User();
                $user->email = trim($request->email);
                $user->role_id = $request->role_id;
                $user->confirmed = 1;
                $user->password = Hash::make($request->password);
                $user->allow_newsletter = isset($data->allow_newsletter) ? $data->allow_newsletter : 0;
                $user->save();

                $member = new Member();
                $member->user_id = $user->id;
                $member->name = $request->name;
                $member->gender = $request->gender;
                $member->mobile_phone = $request->mobile_phone;
                $member->birth_date = (isset($request->birth_date) && $request->birth_date !== '')? Carbon::createFromFormat('d-m-Y', $request->birth_date): null;
                $member->address = $request->address;
                $member->description = $request->description;
                $member->save();
                
                if ($request->file('photo')) 
                {

                    //get the photo data and new path
                    $file = $request->file('photo');
                    //$folderPath = 'uploads/' . $member->id . '/';
                    $folderPath = 'uploads/member/' . $member->id . '/avatar/';

                    $newAvatarPath = $folderPath . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                    // create the directory if its not there, this is a must since intervention did not create the directory automatically
                    File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                    // resize and save the uploaded file            
                    Image::make($file)
                            ->resize(600, 600, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->save($newAvatarPath);

                    $oldAvatarPath = $member->avatar_path;

                    //delete old photo
                    File::delete(public_path() . '/' . $member->avatar_path);

                    // update member photo path when creating image is successfully
                    $member->avatar_path = $newAvatarPath; // upload path

                    event(new ImageDeleted($oldAvatarPath));

                }

                $member->save();

            });
            return redirect()->back()->withTitle('New Member')->withMessage('Data Member telah tersimpan..');

        } catch (Exception $e) {
            return redirect()->route('member-create')->withMessage('Terdapat kesalahan, data member gagal ditambahkan.');
        }
    }

    /**
     * dashboard member
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if(!$user->isAdmin() and !$user->isManager())
        {
            return redirect()->route('edit-profile');
        }

        $member = Member::where('user_id', $user->id)->first();

        $poin = 0;
        
        $totalProductCategories = ProductCategory
::active()->get()->count();
        $totalProducts = Product::active()->get()->count();
        $totalProductPackages = ProductPackage::active()->get()->count();

        $data['totalProductCategories'] = $totalProductCategories;
        $data['totalProducts'] = $totalProducts;
        $data['totalProductCategories'] = $totalProductPackages;

        $data['title'] = 'Dashboard';
        $data['member'] = $member;
    
        return view('dashboard',$data)->withTitle('Summary');
    }

    /**
     * dashboard member
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function activity(Request $request)
    {
        $user = Auth::user();
        $member = Member::with('regionAdmin')->where('user_id', $user->id)->first();

        $activity = Activity::where('member_id',$member->id)->orderBy('created_at','desc')->paginate(10);
        
        $data['title'] = 'Activities';
        $data['activities'] = $activity;
        $data['member'] = $member;
    
        return view('member.activity_member',$data);
    }    

    /**
     * show member profile page
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function myProfile(Request $request)
    {
        return view('member.profile')->withTitle('profile');
    }

    /**
     * edit profile page for member only
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;


        $data['title'] = "Edit Profile";

        
        return view('member.editprofile',$data)
                ->withMember($member)->withProvince(Province::orderBy('name', 'asc'));
    }

    /**
     * update profile for member only
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $data)
    {
        try 
        {
            $otherMessages = '';
            $user = $data->user();
            $user->allow_newsletter = isset($data->allow_newsletter) ? $data->allow_newsletter : 0;
            $user->save();
            
            $member = $user->member;
            $member->name = $data['name'];
            $member->province_id = $data['province'];
            $member->mobile_phone = $data['mobile_phone'];
            $member->gender = $data['gender'];
            $member->school = $data['school'];
            $member->description = $data['description'];
            if($data['birth_date'])
            {
                $birth_date = Carbon::createFromFormat('d-m-Y', $data['birth_date']);
                $member->birth_date = $birth_date->format('Y-m-d');
            }
            $member->address = $data['address'];
            $member->city = $data['city'];
            $member->phone = $data['phone'];

            if ($data->file('photo')) 
            {

                //get the photo data and new path
                $file = $data->file('photo');
                $folderPath = 'uploads/' . $member->id . '/';
                $newAvatarPath = $folderPath . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                        ->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->save($newAvatarPath);

                $oldAvatarPath = $member->avatar_path;

                //delete old photo
                File::delete(public_path() . '/' . $member->avatar_path);

                // update member photo path when creating image is successfully
                $member->avatar_path = $newAvatarPath; // upload path

                event(new ImageDeleted($oldAvatarPath));

            }
            // id card
            if ($data->file('idcard')) 
            {

                //delete old photo


                //get the photo data and new path
                $file = $data->file('idcard');
                $folderPath = 'uploads/' . $member->id . '/';
                $newIdCardPath = $folderPath . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                        ->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->save($newIdCardPath);

                $oldIdCardPath = $member->idcard_path;

                //delete old photo
                File::delete(public_path() . '/' . $member->idcard_path);

                // update member photo path when creating image is successfully
                $member->idcard_path = $newIdCardPath; // upload path

                event(new ImageDeleted($oldIdCardPath));
            }
            
            $member->save();
        } 
        catch (\Exception $e) 
        {
            Log::error($e->getMessage().' || File '.$e->getFile().' || Line '. $e->getLine());
	        return redirect()->route('edit-profile')->withMessage('sorry something wrong. ');
        }
        

        //province doesn't be thrown, because province is thrown in viewcomposer  
        $data['member'] = $member;
        $data['message'] = 'Profile Updated. '.$otherMessages;
        return redirect()->route('edit-profile')->withMessage($data['message']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::where('id',$id)->with('province','educationType','activity','user')->first();
        $activities = Activity::where('member_id',$id)->orderBy('created_at','desc')->paginate(10);
        if (!$member)
            abort('404');  //no such user
        

        //compress and send the data to view
        $data['activities'] = $activities;
        $data['title'] = 'Data Member';
        $data['member'] = $member;
        return view('member.profile',$data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function miniProfile($id)
    {
        $member = Member::where('id',$id)->with('province','educationType','user')->first();
        $member->increment('profile_views');
        
        return view('member.miniprofile')->withMember($member);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function publicProfile($id)
    {
        $member = Member::where('id',$id)->with('province','educationType','user')->first();
        
        $member->increment('profile_views');
        
        //if admin, show all post, not only approved one
        if(Auth::user() and Auth::user()->isAdmin())
        {
            $contentArticles = Content::where('member_id', $member->id)->whereHas('post',function($query){
                                                               return $query->Article();
                                                           })->orderBy('created_at','desc')->with('post.postCategory')->paginate(8);
        }
        else
        {
            $contentArticles = Content::where('member_id', $member->id)->approved()->whereHas('post',function($query){
                                                               return $query->Article();
                                                           })->orderBy('created_at','desc')->with('post.postCategory')->paginate(8);
        }

        if(request()->user()){
            $currentMember = request()->user()->member;
            $data['isSubscribe'] = $currentMember->isSubscribe($member);
        }
        
        $data['contentArticles'] = $contentArticles;
        $data['title'] = $member->name ? ucfirst($member->name) : "";
        return view('member.publicprofile', $data)->withMember($member);
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = Member::find($id);
        return view('member.editmember')
                ->withMember($member)->withProvince(Province::orderBy('name', 'asc'))
                ->withEducation(EducationType::orderBy('id', 'asc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $data)
    {
        $member = Member::find($data->id);
        $member->name = $data['name'];
        $member->province_id = $data['province'];
        $member->mobile_phone = $data['mobile_phone'];
        $member->gender = $data['gender'];
        $member->school = $data['school'];
        $member->description = $data['description'];
        $member->education_type_id = $data['education'];
        $member->birth_date = $data['birth_date']; 
        $member->address = $data['address'];
        $member->city = $data['city'];
        $member->phone = $data['phone'];
        $member->save();

        if ($data->file('photo')) {
            // dd($data);
            //delete old photo
            File::delete(public_path() . '/' . $member->avatar_path);

            //get the photo data and new path
            $file = $data->file('photo');
            $folderPath = 'uploads/' . $member->id . '/';
            $member->avatar_path = $folderPath . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

            // create the directory if its not there, this is a must since intervention did not create the directory automatically
            File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

            // resize and save the uploaded file            
            Image::make($file)
                    ->resize(600, 600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($member->avatar_path);

            //save new path
            $member->save();
        }

        return view('member.editmember')->withMember($member)->withProvince(Province::orderBy('name', 'asc'))->withTitle('Edit Profile')->withMessage('Member Profile Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function status(Request $request)
    {
      $user = User::where('id', '=', $request->id)->first();
      if($user)
      {     
        if($request->action == 'active')    //set the member status active
        {

            $status = 'Active';
            DB::transaction(function () use($request, &$user) {   
                $user->confirmed = 1;
                $user->confirmed_at = Carbon::now();
                if(isset($user->activation_code) and $user->activation_code != '' ){
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

                        // add referral point for this user
                        $activity = new Activity();
                        $activity->member_id = $user->member->id;
                        $activity->activity_type_id = ACTIVITY_REGISTER_FROM_INVITATION;//id activity_type that refer to reference register
                        $activity->content_id = null;
                        $activity->poin = ActivityType::where('id',ACTIVITY_REGISTER_FROM_INVITATION)->first()->poin;
                        $activity->save();
                    }
                    $user->activation_code=null;
                }
            }); 
        }     
        elseif($request->action == 'inactive')  //set the member status inactive
        {
          $user->confirmed = 0;
          $status = 'Inactive';     
        }
        $user->save();
        
        return $status;   
      }
      else
      {
        return response()->json(['status' => 'error'], 500);                  
      } 
    }
    public function addPoint(Request $request)
    {
        try {
            DB::transaction(function () use($request) {   
                    
                $activity = new Activity();
                $activity->member_id = $request->member_id;
                $activity->activity_type_id = ACTIVITY_OTHER;
                $activity->content_id = null;
                $activity->poin = $request->poin;
                $activity->remark = $request->remark;
                $activity->save();
            }); 
            return redirect()->back()->withMessage('Point successfully added..');

        } catch (Exception $e) {

            return redirect()->back()->withError('Failed to add point.');
        }
    }

    public function viewImage(Request $request)
    {
        $data['text'] = $request->text;
        $data['path'] = $request->path;
        return view('misc.viewImage', $data);
    }    

    public function loginAs($id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;

        if (Auth::user()->isSuperAdmin())
        {
            //confirm the user
            Auth::login($user);
            return redirect()->route('member-dashboard')->withMessage('Success.. You are now login as : '.$member->name);
        }
        else
        {
            abort(403);
        }

    }    

    public function subscribeMember(Request $request)
    {
        $publisher = Member::with('subscriber')->find($request->publisher_id);
        $subscriber = $request->user()->member;

        if(!$publisher)
        {
            return response()->json(['status' => 'error', 'message'=>'Publisher not found'], 404);                   
        }
        elseif($subscriber->id == $publisher->id)
        {
            return response()->json(['status' => 'error', 'message'=>'Can not subscribe your self'], 400);                   
        }
        elseif(in_array($subscriber->id, $publisher->subscriber->pluck('pivot.subscriber_id')->toArray()))
        {
            return response()->json(['status' => 'error', 'message'=>'You already subscribe this publisher'], 400);   
        }
        // subscribe publisher
        $publisher->subscriber()->attach([$subscriber->id]);

        return response()->json(['status' => 'success'], 200);                   
    }


    public function unsubscribeMember(Request $request)
    {
        $publisher = Member::find($request->publisher_id);
        $subscriber = $request->user()->member;
        if(!$publisher)
        {
            return response()->json(['status' => 'error', 'message'=>'Publisher not found'], 404);                   
        }
        elseif($subscriber->id == $publisher->id)
        {
            return response()->json(['status' => 'error', 'message'=>'Can not unsubscribe your self'], 400);                   
        }
        elseif(!in_array($subscriber->id, $publisher->subscriber->pluck('pivot.subscriber_id')->toArray()))
        {
            return response()->json(['status' => 'error', 'message'=>'You have not subscribe this publisher yet'], 400);   
        }

        // unsubscribe publisher
        $publisher->subscriber()->detach([$subscriber->id]);

        return response()->json(['status' => 'success'], 200);                   
    }

    public function showSubscriberList(Request $request, $id)
    {
        $member = Member::find($id);

        if($request->ajax())
        {
            $subscribers = $member->subscriber()->take($request->take)->skip($request->skip)->get();
            return response()->json(['subscribers'=>$subscribers]);
        }

        $subscribers = $member->subscriber()->paginate(25);
        $data['title'] = 'Follower';
        $data['subscribers'] = $subscribers;
        return view('member.subscriber_list', $data);
    }


    public function showSubscribeList(Request $request, $id)
    {
        $member = Member::find($id);

        if($request->ajax())
        {
            $subscribers = $member->publisher()->take($request->take)->skip($request->skip)->get();
            return response()->json(['subscribers'=>$subscribers]);
        }

        $publishers = $member->publisher()->paginate(25);
        $data['title'] = 'Following';
        $data['publishers'] = $publishers;

        return view('member.publisher_list', $data);
    }
    
    //unblock member from conversation
    public function unblockMember(Request $request)
    {
        $blockedMember = Member::find($request->blockedMember_id);
        $blocker = $request->user()->member;
        
        if(!$blockedMember)
        {
            return response()->json(['status' => 'error', 'message'=>'Member not found'], 404);                   
        }
        elseif($blocker->id == $blockedMember->id)
        {
            return response()->json(['status' => 'error', 'message'=>'Can not unblock yourself'], 400);                   
        }
        elseif(!in_array($blocker->id, $blockedMember->blocker->pluck('pivot.blocker_id')->toArray()))
        {
            return response()->json(['status' => 'error', 'message'=>'You have not blocked this member yet'], 400);   
        }
        // unblock member
        $blockedMember->blocker()->detach([$blocker->id]);

        return response()->json(['status' => 'success'], 200);                   
    }

    //show prize page to redeem point
    public function showPrize($id)
    {
        $prizes = RedemptionPrize::where('is_active',1)->get();
        $member = Member::find($id);
        $currentYear = Carbon::now()->year;
        $endOfYear = Carbon::now()->endOfYear()->toDateString();

        $usedPoint = $member->activity()->whereYear('created_at', '=', $currentYear)
                                        ->whereHas('redemptionTransaction', function ($q){
                                            $q->where('status_id','!=',STATUS_REJECTED);
                                        })->sum('poin');

        $firstRedeemStart = '2017-05-01'; //first redeem period starts on May 5th
        $data['totalYearlyPoint'] = $member->yearlyPoint($firstRedeemStart,$endOfYear); 
        $data['usedPoint'] = $usedPoint;
        $data['prizes'] = $prizes;
        $data['member_id'] = $id;
        $data['title'] = 'Redeem Your Point';
        return view('member.pointredemption', $data);
    }

    //store redeem transaction to database
    public function redeemPrize($memberid, $prizeid)
    {
        $this->authorize('create',RedemptionTransaction::class);
        $member = Member::find($memberid);
        $prize = RedemptionPrize::find($prizeid);
        $endOfYear = Carbon::now()->endOfYear();

        $flag = 0; 
        if($prize->is_redeemable_once)
        {
            $redeemTransaction = $member->activity()->whereHas('redemptionTransaction', function ($q) use($prize){
                                            $q->where('redemption_prize_id', $prize->id)
                                              ->where('status_id', STATUS_APPROVED);
                                        })->first();
            if($redeemTransaction){
                $flag = 1; //checking if member already has access to send message/ chat
            }
        }

        $currentYear = Carbon::now()->year;
        $usedPoint = $member->activity()->whereYear('created_at', '=', $currentYear)
                                        ->whereHas('redemptionTransaction', function ($q){
                                            $q->where('status_id','!=',STATUS_REJECTED);
                                        })->sum('poin');

        $firstRedeemStart = '2017-05-01'; //first redeem period starts on May 5th
        $yearlyPoint = $member->yearlyPoint($firstRedeemStart, $endOfYear);

        if($prize->point <= ($yearlyPoint-$usedPoint) && !$flag){
            try {
                DB::transaction(function () use($member, $prize, &$message) { 
                    //add new activity   
                    $activity = new Activity();
                    $activity->member_id = $member->id;
                    $activity->activity_type_id = ACTIVITY_REDEEM_POINT;
                    $activity->content_id = null;
                    $activity->poin = $prize->point;
                    $activity->remark = null;
                    $activity->save();

                    //add new redeem transaction with pending as the status
                    $redeemTransaction = new RedemptionTransaction();
                    $redeemTransaction->activity_id = $activity->id;
                    $redeemTransaction->redemption_prize_id = $prize->id;
                    $redeemTransaction->member_id = $member->id;
                    $redeemTransaction->status_id = STATUS_PENDING;
                    $redeemTransaction->redeemed_at = Carbon::now();
                    $redeemTransaction->created_at = Carbon::now();
                    $redeemTransaction->updated_at = Carbon::now();
                    $message='Success';
                    $redeemTransaction->save();

                });

            } catch (Exception $e) {
                $message = 'Failed to redeem point.';
                return redirect()->route('member-dashboard')->withMessage($message);
            }

            return redirect()->back()->withMessage($message);
        }
        else{
            if($flag==1){
                return redirect()->back()->withMessage("You can only win this prize once");
            }
            return redirect()->back()->withMessage("You don't have enough point");
        }
    }

    //randomly picked winners as much as the specified quota for specified prize
    public function pickWinner()
    {
        $startDate = Carbon::now()->day(1);
        $endDate = Carbon::now()->endOfMonth();

        // get redemption prizes
        $prizes = RedemptionPrize::get();
        foreach ($prizes as $key => $prize) 
        {
            //get the list of member who chose the prize
            $prizePool = RedemptionTransaction::where('redemption_prize_id', $prize->id)
                                              ->where('status_id', STATUS_PENDING)
                                              ->whereBetween('redeemed_at', [$startDate, $endDate])
                                              ->with('member')
                                              ->get();

            //randomize the winners if candidate more than quota and set their redemption status as approved
            if($prizePool->count() > $prize->quota)
            {
                $randomWinners = $prizePool->random($prize->quota);
            }
            else
            {
                $randomWinners = $prizePool;
            }

            // Change redemption transaction status
            DB::transaction(function() use($randomWinners, $prizePool){
                foreach ($prizePool as $candidate) 
                {
                    // set status to be approved if candidate is a winner candidates
                    if($randomWinners->pluck(['id'])->contains($candidate->id))
                    {
                        $candidate->status_id = STATUS_APPROVED;   
                    }
                    else
                    {
                        $candidate->status_id = STATUS_REJECTED;      
                    }
                    $candidate->save();
                }
            });

            if($prize->id == 1)
            {
                foreach ($randomWinners as $winner) 
                {
                    // set member's can_converse to 1 so he/she can access chatting option
                    $member = Member::where('id', $winner->member_id)->first();
                    $member->can_chat = 1;
                    $member->save();
                }
            }  
        }

        echo "picked winner successfully";
    }

    public function showMemberRedeemHistory($memberId)
    {
        $currentMember = request()->user()->member;
        if($currentMember->id != $memberId and Gate::denies('manageHistory', RedemptionTransaction::class))
        {
            abort(403);
        }
        $member = Member::find($memberId);
        
        
        $redeemTransactions = RedemptionTransaction::where('member_id', $memberId)
                                              ->with('redemptionPrize', 'status')->paginate(25);

        $data['redeemTransactions'] = $redeemTransactions;
        $data['title'] = 'Redeem History';
        return view('member.redeemhistory', $data);                                      
    }

    public function showAllRedeemHistory(Request $request)
    {

        $redeemTransactions = RedemptionTransaction::with('redemptionPrize', 'member', 'status');
        $winners = RedemptionTransaction::with('status');
        $param[''] = '' ;
        $data['winners'] = 0;
        //search by prize
        if($request->prize){
            $param['prize'] = $request->prize;
            $redeemTransactions = $redeemTransactions->where('redemption_prize_id', $request->prize);
            //check if the winner has chosen
            $winners = $winners->where('redemption_prize_id', $request->prize)
                               ->where('status_id', STATUS_APPROVED);            
        }
        
        //search by redemption period
        if($request->prizeMonth)
        {
            $param['prizeMonth'] = $request->prizeMonth;

            $date = explode("-", $request->prizeMonth);
            $startDate = Carbon::now()->year($date[1])->month($date[0])->day(1);
            $endDate = Carbon::now()->year($date[1])->month($date[0]);
            $endDate->endOfMonth();

            $redeemTransactions = $redeemTransactions->whereBetween('redeemed_at', [$startDate, $endDate]);
            //check if the winner has chosen
            $winners = $winners->whereBetween('redeemed_at', [$startDate, $endDate])
                               ->where('status_id', STATUS_APPROVED);
        }

        //check if the winner has chosen
        if($request->prize && $request->prizeMonth && !$winners->count() && $redeemTransactions->count()){
            $data['winners'] = 1;    
        }
        
        $data['prize_choices'] = RedemptionPrize::orderBy('name')->pluck('name', 'id');
        $data['redeemTransactions'] = $redeemTransactions->paginate(25);
        $data['title'] = 'Member Redemption History';
        $data['param'] = $param;
        return view('member.allredeemhistory', $data);
    }

    //show top members from each province
    public function topMembers()
    {
        $provinces = Province::select('id', 'name')->orderBy('name')->get();
        
        $arrayTopProvinceMembers = [];

        foreach ($provinces as $province) {

            $topProvinceMembers = collect();

            $arrayTopProvinceMembers = Cache::remember('topMember'.$province->id, DB_QUERY_CACHE_PERIOD_MEDIUM, function() use($province,  &$topProvinceMembers, &$arrayTopProvinceMembers) {
                                Member::leftJoin('user','member.user_id','=','user.id')
                                    ->leftJoin('activity','member.id','=','activity.member_id')
                                    ->leftjoin('province','member.province_id','=','province.id')
                                    ->where('user.role_id',[ROLE_MEMBER])
                                    ->whereBetween('activity.created_at', ['2017-05-01', '2017-12-31'])
                                    ->where('member.province_id',$province->id)
                                    ->groupBy('member.user_id')
                                    ->select('member.id as id',
                                            'province.name as province',
                                            'member.name as name',
                                            'birth_date', 'school', 'avatar_path',
                                            DB::raw('if(sum(activity.poin),sum(activity.poin),0) as last_point') )
                                    ->orderBy('id', 'desc')
                                    ->chunk(10000, function ($members) use (&$topProvinceMembers){
                                        $members = $members->sortByDesc('last_point')->take(5);
                                        foreach($members as $member){
                                            $topProvinceMembers->push($member);
                                        }
                                        $topProvinceMembers = $topProvinceMembers->sortByDesc('last_point')->take(5);
                                    });
                            array_push($arrayTopProvinceMembers, $topProvinceMembers);
                            return $arrayTopProvinceMembers;
            });
        }

        $data['provinces'] = $provinces;
        $data['arrayProvinceMembers'] = $arrayTopProvinceMembers;
        $data['title'] = 'Top Member';
        return view('member.topMembers', $data);
    }
}
