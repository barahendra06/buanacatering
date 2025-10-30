<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\PostCategory;
use App\Province;
use App\EducationType;
use App\Member;
use App\Setting;
use App\Posts;
use App\Notification;

use Auth;
use DB;
use Cache;
use Carbon\Carbon;

class Navigation
{

    public function compose(View $view)
    {
        $member=null;
		
        if(Auth::check())
        {
            $user = Auth::user();

            $member = Cache::remember('memberUser'.$user->id, DB_QUERY_CACHE_PERIOD_MEDIUM, function() use($user){
                            return Member::with('user')
                                    ->where('user_id', $user->id)->first();
                        });

           if ($member) {
               // count notification that doesn't read yet
               $notificationCount = Cache::remember('notificationCountMember'.$member->id, DB_QUERY_CACHE_PERIOD_MEDIUM, function() use($member) {
                   return Notification::where('recipient_id', $member->id)->notRead()->count();
               });

               // all user notifications
               $notifications = Cache::remember('notificationMember'.$member->id, DB_QUERY_CACHE_PERIOD_MEDIUM, function() use($member) {
                   return Notification::where('recipient_id', $member->id)->take(NOTIFICATION_LIMIT)->orderBy('created_at', 'desc')->get();;
               });

               $view->with('notifications', $notifications);
               $view->with('notificationCount', $notificationCount);
           }
        }
        else
        {
            $member = null;  
        }
        
        $newMembers = Cache::remember('newMember', DB_QUERY_CACHE_PERIOD_FAST, function(){
                            return Member::whereHas('user', function ($query) {
                                $query->where('confirmed', 1)
								->where('active', 1)
								->where('expired', 0)
								->where('role_id', 1);
                                })->orderBy('created_at','desc')->take(6)->get();
                        });

        $view->with('currentMember',$member)
             ->with('newMembers',$newMembers);
    }
}