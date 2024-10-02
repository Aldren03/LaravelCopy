<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    
    }

    public function isAdmin(User $user)
{
    return $user->usertype === 'admin';
}

public function isManager(User $user)
{
    return $user->usertype === 'Manager';
}

public function isPosting(User $user)
{
    return $user->usertype === 'Posting Clerk';
}

public function isCredit(User $user)
{
    return $user->usertype === 'Credit Investigator';
}

public function isCollector(User $user)
{
    return $user->usertype === 'Collector';
}

public function isBorrower(User $user)
{
    return $user->usertype === 'user';
}

}
