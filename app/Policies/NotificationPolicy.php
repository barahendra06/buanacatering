<?php

namespace App\Policies;

use App\User;
use App\Notification;

class NotificationPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //give full access to admin
    public function before($user, $ability)
    {
        if ($user->isAdmin()) 
        {
            return true;
        }
    }

    public function publish(User $user)
    {
        return $user->isAdmin() or $user->isEditor();
    }

    // SIDEBAR
    public function menu(User $user)
    {
        return $user->isAdmin() or $user->isEditor();
    }

    public function sendNotification(User $user)
    {
        return false;
    }
}
