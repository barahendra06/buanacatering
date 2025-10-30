<?php

namespace App\Policies;

use App\User;
use App\Posts;

class MemberPolicy
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
        if ($user->isAdmin() or $user->isManager()) 
        {
            return true;
        }
    }

    public function create(User $user)
    {
        return $user->isAdmin(); 
    }

    public function store(User $user)
    {
        return $user->isAdmin(); 
    }

    // view detail member
    public function viewDetail(User $user)
    {
        return false; 
    }
}
