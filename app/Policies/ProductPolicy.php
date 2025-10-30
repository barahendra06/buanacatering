<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

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
        if ($user->isAdmin() || $user->isManager()) 
        {
            return true;
        }
    }

    public function list(User $user)
    {
        return $user->isOperational(); 
    }

    public function create(User $user)
    {
        if ($user->isOperational()) 
        {
            return true;
        }
    }

    public function edit(User $user)
    {
        if ($user->isOperational()) 
        {
            return true;
        }
    }


    public function detail(User $user)
    {
        return $user->isOperational(); 
    }

    public function update(User $user)
    {
        if ($user->isOperational()) 
        {
            return true;
        }
    }
}
