<?php

namespace App\Policies;

use App\User;

class UserPolicy
{

    //give full access to admin
    public function before(User $user, $ability)
    {
        if ($user->isAdmin() or $user->isManager()) 
        {
            return true;
        }
    }
}
