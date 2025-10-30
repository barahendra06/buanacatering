<?php

namespace App\Listeners;
use App\Activity;
use App\ActivityType;
use App\Badge;
use App\BadgeMember;
use App\Comment;
use App\Content;
use App\Member;
use App\PollParticipant;
use App\RedemptionTransaction;


class ActivityListener
{


    //----------------------------- CHALLENGE ACTIVITY -------------------------------

    public function onChallengeContentApproved($event) 
    {
        $challenge = $event->challenge;
        $content = $event->content;
        // check challenge whether challenge is "single entry" or "multiple entry"
        if($challenge->isSingleEntry()) 
        {
            // if single entry, so check challenge activity is exist. if exist, don't give point
            $activity = Activity::where('member_id', $content->member_id)
                                ->whereHas('content',function($query) use($content){
                                    $query->where('challenge_id',$content->challenge_id);
                                  })
                                ->where('poin','<>',0)
                                ->first();

            // if activity (that has point) not found, add point to user
            if(!$activity)
            {
                $activityApproved = new Activity();
                $activityApproved->member_id = $content->member_id;
                if($content->isPhoto())
                {
                    $activityApproved->activity_type_id = ACTIVITY_PHOTO_APPROVED;// activity for approving photo
                }
                else if($content->isGraphic())
                {
                    $activityApproved->activity_type_id = ACTIVITY_GRAPHIC_APPROVED;// activity for approving graphic    
                }
                else if($content->isPost())
                {
                    $activityApproved->activity_type_id = ACTIVITY_POST_APPROVED;// activity for approving post
                }
                $activityApproved->poin = $challenge->poin;
                $activityApproved->content_id = $content->id;
                $activityApproved->save();
            } 
            else
            {
                $activityApproved = new Activity();
                $activityApproved->member_id = $content->member_id;
                if($content->isPhoto())
                {
                    $activityApproved->activity_type_id = ACTIVITY_PHOTO_APPROVED;// activity for approving photo    
                }
                else if($content->isGraphic())
                {
                    $activityApproved->activity_type_id = ACTIVITY_GRAPHIC_APPROVED;// activity for approving graphic    
                }
                else if($content->isPost())
                {
                    $activityApproved->activity_type_id = ACTIVITY_POST_APPROVED;// activity for approving post
                }
                $activityApproved->poin = 0;
                $activityApproved->content_id = $content->id;
                $activityApproved->save();  
            }
        }
        else  //chalenge is multiple entry. so, give it point for each contents (no need to check existance challenge activity) 
        {
            // find activity for adding poin
            $activity = Activity::where('content_id', $content->id)->first();
            

            // add activity for annouce that content user is approved 
            $activityApproved = new Activity();
            $activityApproved->member_id = $activity->member_id;
            if($content->isPhoto())
            {
                $activityApproved->activity_type_id = ACTIVITY_PHOTO_APPROVED;// activity for approving photo    
            }
            else if($content->isGraphic())
            {
                $activityApproved->activity_type_id = ACTIVITY_GRAPHIC_APPROVED;// activity for approving graphic    
            }
            else if($content->isPost())
            {
                $activityApproved->activity_type_id = ACTIVITY_POST_APPROVED;// activity for approving post
            }
            
            $activityApproved->content_id = $content->id;
            $activityApproved->poin = $challenge->poin;
            $activityApproved->save();
        }
    }

    public function onChallengeContentRejected($event) 
    {
        $challenge = $event->challenge;
        $content = $event->content;

        $activityRejected = new Activity();
        $activityRejected->member_id = $content->member_id;

        if($content->isPhoto())
        {
            $activityRejected->activity_type_id = ACTIVITY_PHOTO_REJECTED;// activity for rejecting photo
            $activityType = ActivityType::where('id', ACTIVITY_PHOTO_REJECTED)->first();   
        }
        else if($content->isGraphic())
        {
            $activityRejected->activity_type_id = ACTIVITY_GRAPHIC_REJECTED;// activity for rejecting graphic  
            $activityType = ActivityType::where('id', ACTIVITY_GRAPHIC_REJECTED)->first(); 
        }
        else if($content->isPost())
        {
            $activityRejected->activity_type_id = ACTIVITY_POST_REJECTED;// activity for rejecting post
            $activityType = ActivityType::where('id', ACTIVITY_POST_REJECTED)->first();
        }
        $activityRejected->poin = $activityType->poin;
        $activityRejected->content_id = $content->id;
        $activityRejected->save();
    }

    public function onChallengeContentWinner($event) {

    }

    public function onChallengeContentPremium($event) {
        
    }

    // ---------------------------------- POLL ACTIVITY ---------------------------

    public function onPollFilled($pollParticipant) 
    {
        //save the activity for fill the poll
        $activity = new Activity();
        $activity->member_id = $pollParticipant->member->id;
        $activity->activity_type_id = ACTIVITY_POLL;//id activity_type that refer to register
        $activity->content_id = $pollParticipant->poll->content_id;
        $activity->poin = ActivityType::where('id',ACTIVITY_POLL)->first()->poin;
        $activity->save();              


        // save the activity for the reference
        if(request()->reference_id && request()->reference_id!=$pollParticipant->member->user->id)
        {
            $referenceMember = Member::where('user_id', request()->reference_id)->first();

            if($referenceMember)
            {
                $activity = new Activity();
                $activity->member_id = $pollParticipant->member->id;
                $activity->activity_type_id = ACTIVITY_REFERENCE_POLLING;//id activity_type using reference code when filling poll
                $activity->content_id = $pollParticipant->poll->content_id;
                $activity->poin = ActivityType::where('id',ACTIVITY_REFERENCE_POLLING)->first()->poin;
                $activity->save();   


                //save activity for the referred member
                $referringActivity = new Activity();
                $referringActivity->member_id = $referenceMember->id;
                $referringActivity->activity_type_id = ACTIVITY_REFERRED_POLLING;//id activity_type that refer to register
                $referringActivity->content_id = $pollParticipant->poll->content_id;
                $referringActivity->poin = ActivityType::where('id',ACTIVITY_REFERRED_POLLING)->first()->poin;
                $referringActivity->save();  

            }
        }               
        
    }

    // ---------------------------------- COMMENT ACTIVITY ---------------------------    

    public function onCommentCreated($comment) 
    {
        //insert activity and give point if it is the first time in a post
        $activity = new Activity();
        $activity->member_id = $comment->member_id;
        $activity->activity_type_id = ACTIVITY_COMMENT;//id activity_type that refer to comment

        $activity->content_id = $comment->content_id;
        if(!Comment::where('content_id',$comment->content_id)->where('member_id',$comment->member_id)->get()->count())
        {
            $activity->poin = ActivityType::where('id',ACTIVITY_COMMENT)->first()->poin;
        }
        $activity->save();

        // update badge
        $badges = Badge::where('activity_type_id', ACTIVITY_COMMENT)->get();
        // count total Activity for commenting in article
        $memberActivityCount = Activity::where('member_id', $comment->member_id)->where('activity_type_id', ACTIVITY_COMMENT)->get()->count();
        
        foreach ($badges as $key => $badge) 
        {
            if($memberActivityCount >= $badge->requirement)
            {
                // get member badge. if exist, dont insert again
                $badgeMember = BadgeMember::firstOrCreate(['badge_id'=>$badge->id, 'member_id'=>$comment->member_id]);
            }
        }

    }

    public function onArticleApproved($event)
    {

        $activity = new Activity();
        $activity->member_id = $event->member->id;
        $activity->activity_type_id = ACTIVITY_ARTICLE_APPROVED;//id activity_type that refer to register
        $activity->content_id = $event->content->id;

        // prevent user get more point if article publish twice
        // check if article member ever been approved
        // if false, add point
        if(!Activity::where('content_id',$event->content->id)
                    ->where('member_id',$event->member->id)
                    ->where('activity_type_id',ACTIVITY_ARTICLE_APPROVED)->sum('poin'))
        {

            // update all spammed and rejected content that reference this content to be 0
            Activity::where('content_id',$event->content->id)
                    ->where('member_id',$event->member->id)
                    ->whereIn('activity_type_id',[ACTIVITY_ARTICLE_REJECTED,ACTIVITY_ARTICLE_SPAMMED])->update(['poin'=>0]);

            $activity->poin = ActivityType::where('id',ACTIVITY_ARTICLE_APPROVED)->first()->poin;

        }
        $activity->save();  

    }


    public function onArticleRejected($event)
    {

        $activity = new Activity();
        $activity->member_id = $event->member->id;
        $activity->activity_type_id = ACTIVITY_ARTICLE_REJECTED;//id activity_type that refer to register
        $activity->content_id = $event->content->id;

        $activity->poin = ActivityType::where('id',ACTIVITY_ARTICLE_REJECTED)->first()->poin;
        $activity->save();  
        // update all approved content that reference this content to be 0
        Activity::where('content_id',$event->content->id)
                ->where('member_id',$event->member->id)
                ->whereIn('activity_type_id',[ACTIVITY_ARTICLE_APPROVED,ACTIVITY_ARTICLE_SPAMMED])->update(['poin'=>0]);
    }

    public function onArticleSpammed($event)
    {

        $activity = new Activity();
        $activity->member_id = $event->member->id;
        $activity->activity_type_id = ACTIVITY_ARTICLE_SPAMMED;//id activity_type that refer to register
        $activity->content_id = $event->content->id;

        $activity->poin = ActivityType::where('id',ACTIVITY_ARTICLE_SPAMMED)->first()->poin;
        $activity->save();  

        // update all approved content that reference this content to be 0
        Activity::where('content_id',$event->content->id)
                ->where('member_id',$event->member->id)
                ->whereIn('activity_type_id',[ACTIVITY_ARTICLE_APPROVED,ACTIVITY_ARTICLE_REJECTED])->update(['poin'=>0]);

    }


    public function onArticleSubmited($event)
    {
        $activity = new Activity();
        $activity->member_id = $event->member->id;
        $activity->activity_type_id = ACTIVITY_ARTICLE_SUBMITED;//id activity_type that refer to register
        $activity->content_id = $event->content->id;
        $activity->poin = ActivityType::where('id',ACTIVITY_ARTICLE_SUBMITED)->first()->poin;
        $activity->save();  

    }

    public function onBadgeMemberSaved($memberBadge)
    {
        // give activity for getting new badge
        $activity = new Activity();
        $activity->member_id = $memberBadge->member_id;
        $activity->activity_type_id = ACTIVITY_NEW_BADGE;//id activity_type that refer to register
        $activity->content_id = null;
        $activity->remark = isset($memberBadge->badge->name) ? $memberBadge->badge->name : null;
        $activity->poin = ActivityType::where('id',ACTIVITY_NEW_BADGE)->first()->poin;
        $activity->save();  
    }


    public function onContentCreated($content)
    {
    
    }
    
    public function onCommentLikeCreated($commentLike)
    {
    }

    public function onRedemptionTransactionUpdated($redemptionTransaction)
    {
        // create activity for user whose redemption is rejected or approved
        $redemptionTransactionOriginal = $redemptionTransaction->getOriginal();

        if($redemptionTransactionOriginal['status_id'] == STATUS_PENDING and 
            ($redemptionTransaction->status_id == STATUS_APPROVED or $redemptionTransaction->status_id == STATUS_REJECTED))
        {
            // give activity for getting new badge
            $activity = new Activity();
            $activity->member_id = $redemptionTransaction->member_id;
            if($redemptionTransaction->status_id == STATUS_APPROVED)
            {
                $activity->activity_type_id = ACTIVITY_REDEMPTION_APPROVED;
                $activity->poin = ActivityType::where('id',ACTIVITY_REDEMPTION_APPROVED)->first()->poin;
                $activity->remark = $redemptionTransaction->redemptionPrize->name;
            }
            else
            {
                $activity->activity_type_id = ACTIVITY_REDEMPTION_REJECTED;
                $activity->poin = ActivityType::where('id',ACTIVITY_REDEMPTION_REJECTED)->first()->poin;
                $activity->remark = $redemptionTransaction->redemptionPrize->name;
            }
            $activity->content_id = null;
            $activity->save();  
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        /*
        $events->listen(
            'App\Events\ContentArticleStored',
            'App\Listeners\ContentEventListener@onContentArticleStored'
        );

        $events->listen(
            'App\Events\ContentArticleUpdated',
            'App\Listeners\ContentEventListener@onContentArticleUpdated'
        );*/
    }
}