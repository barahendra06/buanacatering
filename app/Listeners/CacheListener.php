<?php

namespace App\Listeners;

use Cache;

class CacheListener
{
    // ---------------------------------- SETTING CACHE ---------------------------

    public function onSettingSaved($setting) 
    {
        Cache::forget('siteSetting');
    }

    // ---------------------------------- CONTENT CACHE ---------------------------

    public function onContentCreated($content) 
    {
    }

    // ---------------------------------- POST CACHE ---------------------------    

    public function onPostSaved($event) 
    {
        Cache::tags('posts')->flush();
    }

    // ---------------------------------- NOTIFICATION CACHE ---------------------------

    public function onNotificationSaved($notification)
    {
        Cache::forget('notificationCountMember'.$notification->recipient_id);
        Cache::forget('notificationMember'.$notification->recipient_id);
    }

    // ---------------------------------- MEMBER CACHE ---------------------------

    public function onMemberSaved($member)
    {
        Cache::forget('memberUser'.$member->user_id);
    }    

    // ---------------------------------- CONVERSATION CACHE ----------------------------

    public function onConversationMessageRecipientCreated($conversationMessageRecipient)
    {
        Cache::forget('newConversationCountMember'.$conversationMessageRecipient->recipient_id);
        Cache::forget('notificationMessageMember'.$conversationMessageRecipient->recipient_id);
    }

    public function onConversationMessageRecipientRead($event)
    {
        $sender = $event->sender;
        Cache::forget('newConversationCountMember'.$sender->id);
        Cache::forget('notificationMessageMember'.$sender->id);   
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {

    }

}