<?php

namespace App\Listeners;

use App\Notification;
use App\Comment;
use App\Event;
use App\Badge;
use Cache;

// Arka - 240724
use App\NotificationTemplate;
use App\PushNotificationTemplate;
use App\Services\NotificationManager;
use App\Services\PushNotificationManager;

class NotificationListener
{
    // ---------------------------------- CHALLENGE NOTIFICATIONS ---------------------------

    public function onChallengeContentApproved($event)
    {
        // give notification to recipient member
        // create notification object, then update activity type id in below logic
        $notification = new Notification();
	 
        $content = $event->content;
        
        if($content->isPhoto())
        {
            // activity for rejecting photo
            $notification->activity_type_id = ACTIVITY_PHOTO_APPROVED;
        }
        else if($content->isGraphic())
        {
            // activity for rejecting graphic  
            $notification->activity_type_id = ACTIVITY_GRAPHIC_APPROVED; 
        }
        else if($content->isPost())
        {
            // activity for rejecting post
            $notification->activity_type_id = ACTIVITY_POST_APPROVED;
        }
        $notification->recipient_id = $event->content->member_id;
        $notification->sender_id = auth()->user()->member->id;

        $notification->text = 'approved your '.$event->content->contentType->name.' in "'.$event->challenge->name.'" challenge';
        
        $notification->link = "#";
        $notification->is_read = 0;
        $notification->save();
    }
    
    public function onChallengeContentRejected($event)
    {
        // give notification to recipient member
        $notification = new Notification();
        $content = $event->content;
        
        if($content->isPhoto())
        {
            // activity for rejecting photo
            $notification->activity_type_id = ACTIVITY_PHOTO_REJECTED;
        }
        else if($content->isGraphic())
        {
            // activity for rejecting graphic  
            $notification->activity_type_id = ACTIVITY_GRAPHIC_REJECTED; 
        }
        else if($content->isPost())
        {
            // activity for rejecting post
            $notification->activity_type_id = ACTIVITY_POST_REJECTED;
        }

        $notification->recipient_id = $event->content->member_id;
        $notification->sender_id = auth()->user()->member->id;

        $notification->text = 'rejected your '.$event->content->contentType->name.' in "'.$event->challenge->name.'" challenge. <b>Note:</b> "'.$event->content->challengeNote()->first()->description.'"';
        
        $notification->link = "#";
        $notification->is_read = 0;
        $notification->save();
    }

    /**
     * Challenge winner
     */
    public function onChallengeContentWinner($event) 
    {
        // prepare notification 
        $notification = new Notification();
        $notification->recipient_id = $event->content->member_id;
        $notification->sender_id = auth()->user()->member->id;
        // update notification attribute
        $notification->activity_type_id = $event->activity->activity_type_id;
        
        $notification->text = 'picked your '.$event->content->contentType->name.' to be <b>winner '.$event->content->contentType->name.'</b> in "'.$event->challenge->name. '" challenge';
        
        // continue update notification attribute and save it
        $notification->link = "#";
        $notification->is_read = 0;
        $notification->save();
    }

    public function onChallengeContentPremium($event) 
    {
        // prepare notification 
        $notification = new Notification();
        $notification->recipient_id = $event->content->member_id;
        $notification->sender_id = auth()->user()->member->id;
        // update notification attribute
        $notification->activity_type_id = $event->activity->activity_type_id;
        
        $notification->text = 'picked your '.$event->content->contentType->name.' to be <b>premium '.$event->content->contentType->name.'</b> in "'.$event->challenge->name.'" challenge';

        // continue update notification attribute and save it
        $notification->link = "#";
        $notification->is_read = 0;
        $notification->save();
    }

    // ---------------------------------- COMMENT NOTIFICATIONS ---------------------------
    public function onCommentCreated($comment) 
    {
        $parentComment = Comment::find($comment->parent_id);

        if($parentComment)
        {
            //if the parent comment and child comment is from the same recipient, doesn't need to create notification
            if($parentComment->member_id == $comment->member_id)
            {
                return;
            }

            // give notification to recipient member that recipient comment is replyed
            $notification = new Notification(); 
            $notification->recipient_id = $parentComment->member_id;
            $notification->sender_id = $comment->member_id;
            $notification->activity_type_id = ACTIVITY_REPLY_COMMENT;

            $post = $comment->content->post;
            
            $notification->text = 'replied your comment in "' . $post->title . '"';
            $notification->link = route('post-single', [$post->id, $post->slug]);
            $notification->is_read = 0;
            $notification->save();
        }
    }

    public function onCommentLikeCreated($commentLike) 
    {
        $comment = Comment::find($commentLike->comment_id);

        if($comment)
        {
            //if the parent comment and child comment is from the same recipient, doesn't need to create notification
            if($commentLike->member_id == $comment->member_id)
            {
                return;
            }

            $notification = new Notification();
            $notification->recipient_id = $comment->member_id;
            $notification->sender_id = $commentLike->member_id;
            $notification->activity_type_id = ACTIVITY_LIKE_COMMENT;
            
            $post = $comment->content->post;

            $notification->text = 'liked your comment in "' .$post->title. '"';
            $notification->link = route('post-single', [$post->id, $post->slug]);
            $notification->is_read = 0;
            $notification->save();
        }
    }


    public function onCommentUpdated($comment)
    {
        $originalComment = $comment->getOriginal();
        
        //check for comment premium
        if($comment->is_premium and !$originalComment['is_premium'])
        {
            $notification = new Notification(); 
            $notification->recipient_id = $comment->member_id;
            $notification->sender_id = null;
            $notification->activity_type_id = ACTIVITY_PREMIUM_COMMENT;

            $post = $comment->content->post;

            $notification->text = 'You got <b>premium comment point</b> in "'.$post->title.'"';

            $notification->link = route('post-single', [$post->id, $post->slug]);
            $notification->is_read = 0;
            $notification->save();
        }
    }


    // ---------------------------------- EVENT NOTIFICATIONS ---------------------------
    public function onEventParticipantUpdated($eventParticipant)
    {
        $originalEventParticipant = $eventParticipant->getOriginal();
        $event = Event::find($eventParticipant->event_id);

        // for individual event and individual team member status, "Approve" action only
        if($originalEventParticipant['status_id'] != $eventParticipant->status_id)
        {

            $notification = new Notification(); 

            $notification->recipient_id = $eventParticipant->member_id;
            $notification->sender_id = auth()->user()->member->id;

            if($eventParticipant->status_id==STATUS_APPROVED)
            {
                $notification->activity_type_id = ACTIVITY_APPROVE_EVENT_PARTICIPANT;
                $notification->text = '<b>approved</b> you in "' . $event->name . ' Event"';
            }
            elseif($eventParticipant->status_id==STATUS_REJECTED)
            {
                $notification->activity_type_id = ACTIVITY_REJECT_EVENT_PARTICIPANT;
                $notification->text = '<b>rejected</b> you in "' . $event->name . ' Event"';
            }
            else
            {
                $notification->activity_type_id = ACTIVITY_PENDING_EVENT_PARTICIPANT;
                $notification->text = 'change your status become <b>pending</b> in "' . $event->name . ' Event"';
            }
            $notification->link = route('event-show', [$event->id]);
            $notification->is_read = 0;
            $notification->save();

            // if event is team, also send notif to leader 
            if($event->is_team)
            {
                $eventTeam = $eventParticipant->eventTeam;

                // this participant is not a leader
                if($eventTeam->leader_id != $eventParticipant->member_id)
                {
                    $notification = new Notification(); 

                    $notification->recipient_id = $eventTeam->leader_id;
                    $notification->sender_id = auth()->user()->member->id;

                    if($eventParticipant->status_id==STATUS_APPROVED)
                    {
                        $notification->activity_type_id = ACTIVITY_APPROVE_EVENT_PARTICIPANT;
                        $notification->text = '<b>approved '.$eventParticipant->member->name.'</b> in "' . $event->name . ' Event"';
                    }
                    elseif($eventParticipant->status_id==STATUS_REJECTED)
                    {
                        $notification->activity_type_id = ACTIVITY_REJECT_EVENT_PARTICIPANT;
                        $notification->text = '<b>rejected '.$eventParticipant->member->name.'</b> in "' . $event->name . ' Event"';
                    }
                    else
                    {
                        $notification->activity_type_id = ACTIVITY_PENDING_EVENT_PARTICIPANT;
                        $notification->text = "change <b>".$eventParticipant->member->name."'s status</b> become <b>pending</b> in". '"' . $event->name . ' Event"';
                    }
                    $notification->link = route('event-show', [$event->id]);
                    $notification->is_read = 0;
                    $notification->save();
                }
            }
        }
    }

    public function onEventTeamUpdated($eventTeam)
    {
        $originalEventTeam = $eventTeam->getOriginal();
        $event = Event::find($eventTeam->event_id);

        // for team event and "Approve" action only
        if($event->is_team and $originalEventTeam['status_id']!=$eventTeam->status_id and $eventTeam->status_id==STATUS_APPROVED)
        {
            foreach ($eventTeam->eventParticipant as $participant) 
            {
                $notification = new Notification(); 

                $notification->recipient_id = $participant->member_id;
                $notification->sender_id = auth()->user()->member->id;
                $notification->activity_type_id = ACTIVITY_APPROVE_EVENT_PARTICIPANT_TEAM;

                $notification->text = 'approved your team in "' . $event->name . ' Event"';
                $notification->link = route('event-show', [$event->id]);
                $notification->is_read = 0;
                $notification->save();
            }
        }
    }

    public function onEventParticipantCreated($eventParticipant)
    {
        $event = Event::find($eventParticipant->event_id);

        // notify member when member invited to team
        if($event->is_team and $eventParticipant->event_team_status==STATUS_INVITED)
        {
            $notification = new Notification(); 

            $notification->recipient_id = $eventParticipant->member_id;
            $notification->sender_id = auth()->user()->member->id;
            $notification->activity_type_id = ACTIVITY_INVITE_MEMBER_TO_JOIN_TEAM;

            $notification->text = 'invites you to join "' . $event->name;
            $notification->link = route('event-show', [$event->id])."#invitationSection";
            $notification->is_read = 0;
            $notification->save();

            Cache::forget('notificationMember'.$eventParticipant->member_id);
        }
    }


    // ---------------------------------- ARTICLE NOTIFICATIONS ---------------------------
    public function onArticleApproved($event)
    {
        // prepare notification 
        $notification = new Notification();
        $notification->recipient_id = $event->member->id;
        $notification->sender_id = $event->member->id;
        // update notification attribute
        $notification->activity_type_id = ACTIVITY_ARTICLE_APPROVED;
        
        $notification->text = 'Congratulations, your article already published now.';

        // continue update notification attribute and save it
        $notification->link = route('post-single', [$event->post->id, $event->post->getOriginal()['slug']]);
        $notification->is_read = 0;
        $notification->save();

        Cache::forget('notificationMember'.$event->member->id);
    }


    public function onArticleRejected($event)
    {
        // prepare notification 
        $notification = new Notification();
        $notification->recipient_id = $event->member->id;
        $notification->sender_id = $event->member->id;
        // update notification attribute
        $notification->activity_type_id = ACTIVITY_ARTICLE_REJECTED;
        
        $notification->text = 'Sorry, your article rejected by Admin. Keep create new good article and admin will moderate again.';

        // continue update notification attribute and save it
        $notification->link = route('article-edit',$event->post->id);
        $notification->is_read = 0;
        $notification->save();

        Cache::forget('notificationMember'.$event->member->id);
    }


    public function onArticleSpammed($event)
    {
        // prepare notification 
        $notification = new Notification();
        $notification->recipient_id = $event->member->id;
        $notification->sender_id = $event->member->id;
        // update notification attribute
        $notification->activity_type_id = ACTIVITY_ARTICLE_SPAMMED;
        
        $notification->text = 'Sorry, your article detected as spam by Admin. Keep create new good article and admin will moderate again.';

        // continue update notification attribute and save it
        $notification->link = route('article-edit',$event->post->id);
        $notification->is_read = 0;
        $notification->save();

        Cache::forget('notificationMember'.$event->member->id);
    }



    public function onArticleSubmited($event)
    {
        // no need to notify users when they submit article
    }


    public function onBadgeMemberSaved($badgeMember)
    {
        $badge = Badge::find($badgeMember->badge_id);

        // prepare notification 
        $notification = new Notification();
        $notification->recipient_id = $badgeMember->member_id;
        $notification->sender_id = $badgeMember->member_id;
        // update notification attribute
        $notification->activity_type_id = ACTIVITY_NEW_BADGE;
        
        $notification->text = 'Congratulations, you got <b>'.$badge->name.' Badge</b>.';

        // continue update notification attribute and save it
        $notification->link = '#';
        $notification->is_read = 0;
        $notification->save();

        Cache::forget('notificationMember'.$badgeMember->member_id);
    }

    public function onRedemptionTransactionUpdated($redemptionTransaction)
    {
        // Notify user whose redemption is rejected or approved
        $redemptionTransactionOriginal = $redemptionTransaction->getOriginal();

        if($redemptionTransactionOriginal['status_id'] == STATUS_PENDING and 
            ($redemptionTransaction->status_id == STATUS_APPROVED or $redemptionTransaction->status_id == STATUS_REJECTED))
        {
            // prepare notification 
            $notification = new Notification();
            $notification->recipient_id = $redemptionTransaction->member_id;
            $notification->sender_id = null;

            // update notification attribute
            if($redemptionTransaction->status_id == STATUS_APPROVED)
            {
                $notification->activity_type_id = ACTIVITY_REDEMPTION_APPROVED;   
                $notification->text = 'Congratulations, your redemption in "'.$redemptionTransaction->redemptionPrize->name.'" was chosen by user.com.';   
            }
            else
            {
                $notification->activity_type_id = ACTIVITY_REDEMPTION_REJECTED;   
                $notification->text = 'Sorry, your redemption in "'.$redemptionTransaction->redemptionPrize->name.'" is not lucky for this month. Please try again next month.';   
            }

            // continue update notification attribute and save it
            $notification->link = route('redeem-history',$redemptionTransaction->member_id);
            $notification->is_read = 0;
            $notification->save();

            Cache::forget('notificationMember'.$redemptionTransaction->member_id);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {

    }

    public function onInvoiceCreated($invoice)
    {
        try
        {
            // Send inbox and push notification when new invoice createds
            $sendNotification = NotificationManager::invoiceCreated($invoice);
        }
        catch(\Exception $e)
        {
            Log::error($e->getMessage());
        }
    }
}