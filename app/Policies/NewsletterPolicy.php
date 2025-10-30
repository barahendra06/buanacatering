<?php

namespace App\Policies;

use App\User;
use App\Posts;

class NewsletterPolicy
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

}
