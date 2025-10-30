<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

// instance of Employee class will refer to employee table in database
class Member extends Model
{

    protected $table = 'member';

    //restricts columns from modifying
    protected $guarded = ['id'];

	protected $appends = array('age','ActivitiesCount', 'joinDate');
	
    public function getAgeAttribute()
    {
		if($this->birth_date)
		{
			$age = Carbon::createFromFormat('Y-m-d', $this->birth_date)->diffInYears();    
			if($age>100)  //invalid age
			{
				$age = null;
			}
			return $age;
		}
		else
		{
			return null;
		}
    }

    public function getJoinDateAttribute()
    {
        return $this->created_at->format("d M Y");    
    }

    public function activityCountRelation()
    {
        return $this->hasOne('App\Activity')->selectRaw('member_id, sum(poin) as sum')
                                            ->whereNotIn('activity_type_id',[ACTIVITY_REDEEM_POINT])->groupBy('member_id');
    }

    public function getActivitiesCountAttribute()
    {
        // if relation is not loaded already, let's do it first
        if ( ! array_key_exists('activityCountRelation', $this->relations)) 
        {
            $this->load('activityCountRelation');
        }

        $related = $this->getRelation('activityCountRelation');

        // then return the count directly
        return ($related) ? (int) $related->sum : 0;
    }

    public function getAvatarPathViewAttribute()
    {
        if($this->avatar_path)
        {
            return $this->avatar_path;
        }
        else
        {
            return 'img/logo-square.jpg';    
        }
    }    
     


    // one member is owned by one user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // one member will be listed as top member
    public function pointSummary()
    {
        return $this->hasOne('App\PointSummary');
    }

    // one member has many posts
    public function editedPost()
    {
        return $this->hasMany('App\Posts','editor_id');
    }	
    // one member has many posts
    public function post()
    {
        return $this->hasMany('App\Posts');
    }

    public function commentLike()
    {
        return $this->hasMany('App\CommentLike');
    }


    public function conversationMessage()
    {
        return $this->hasMany('App\ConversationMessage');
    }

    public function conversationParticipant()
    {
        return $this->hasMany('App\ConversationParticipant');
    }

    public function conversationMessageRecipient()
    {
        return $this->hasMany('App\ConversationMessageRecipient');
    }

    // one member has many comments
    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    // one member has many photos
    public function photo()
    {
        return $this->hasMany('App\Photos');
    }

    // one member has many infografis
    public function infografis()
    {
        return $this->hasMany('App\Infografis');
    }

    // one member has many activities
    public function activity()
    {
        return $this->hasMany('App\Activity');
    }
	
	// one member has many responses
    public function response()
    {
        return $this->hasMany('App\Response');
    }
	
	// one member has many activities
    public function pollParticipant()
    {
        return $this->hasMany('App\PollParticipant');
    }
	
	// one member has many answer
    public function pollAnswer()
    {
        return $this->hasMany('App\PollAnswer');
    }	

    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    public function notification()
    {
        return $this->hasMany('App\Notification', 'recipient_id');
    }

    public function content()
    {
        return $this->hasMany('App\Content');
    }

    // one member has many report
    public function contentReports(){
        return $this->hasMany('App\ContentReport');
    }

    public function educationType()
    {
        return $this->belongsTo('App\EducationType');
    }

    public function eventParticipant()
    {
        return $this->hasMany('App\EventParticipant');
    }
    
    public function regionAdmin()
    {
        return $this->belongsToMany('App\Province', 'region_admin', 'member_id', 'province_id');
    }

    public function badge()
    {
        return $this->belongsToMany('App\Badge')->withTimestamps();
    }


    public function badgeMember()
    {
        return $this->hasMany('App\BadgeMember');
    }


    public function challengeVote()
    {
        return $this->hasMany('App\ChallengeVote');
    }

    public function publisher()
    {
        return $this->belongsToMany('App\Member', 'member_subscription', 'subscriber_id','publisher_id')->withTimestamps();
    }

    public function subscriber()
    {
        return $this->belongsToMany('App\Member', 'member_subscription', 'publisher_id' ,'subscriber_id')->withTimestamps();
    }    

    public function blocker()
    {
        return $this->belongsToMany('App\Member', 'member_blocked', 'blocked_id','blocker_id')->withTimestamps();
    }

    public function blockedMember()
    {
        return $this->belongsToMany('App\Member', 'member_blocked', 'blocker_id' ,'blocked_id')->withTimestamps();
    }

    public function isBlocked($memberId)
    {
        try
        {   //check if this member is on the blocked list of $member
            if($this->blocker()->pluck('member.id')->contains($memberId))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public function isBlocking($memberId)
    {
        try
        {   //check if $member is on the blocked list of this member
            if($this->blockedMember()->pluck('member.id')->contains($memberId)) 
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    // one member has many prize redemption
    public function redemptionTransactions(){
        return $this->hasMany('App\redemptionTransaction');
    }

    public function isRegionAdmin()
    {
        try
        {
            $regionAdmin = $this->regionAdmin;
            if($regionAdmin and $regionAdmin->count())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
        
    }

    public function isSubscribe($publisher)
    {
        try
        {
            if($this->publisher()->pluck('member.id')->contains($publisher->id))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public function getProvinceNameForRegionAdmin(array $id)
    {
        $provinceNameForRegionAdmin =  Province::select('name')->whereIn('id', $id)->get();

        $provinceNameForRegionAdmin = $provinceNameForRegionAdmin ? implode(", ", $provinceNameForRegionAdmin->pluck('name')->toArray()) : "-";

        return $provinceNameForRegionAdmin;
                
    }


    public function getTotalPointAttribute()
    {
        $totalPoint = $this->activity()->selectRaw('sum(poin) as total_point')->first();
        if($totalPoint and isset($totalPoint->total_point))
        {
            return (int)$totalPoint->total_point;
        }
        else
        {
            return 0;
        }
    }

    public function getProvinceNameAttribute()
    {
        if($this->province_id)
        {
            return Province::select('name')->find($this->province_id)->name;    
        }
        else
        {
            return null;
        }
        
    }

    public function yearlyPoint($startDate, $endDate){

        $totalPointYearly = $this->activity()->whereBetween('created_at', [$startDate, $endDate])
                                             ->whereNotIn('activity_type_id',[ACTIVITY_REDEEM_POINT])
                                             ->sum('poin');

        $yearlyPoint = $totalPointYearly;
        
        if($yearlyPoint and isset($yearlyPoint)){
            return $yearlyPoint;
        }
        else{
           return 0;
        }
    }

    public function getTotalSubscribeAttribute()
    {      
        return $this->publisher()->count();
    }

    public function getTotalSubscriberAttribute()
    {      
        return $this->subscriber()->count();
    }

    public function olcourseParticipant()
    {
        return $this->hasOne(OlcourseParticipant::class);
    }
}