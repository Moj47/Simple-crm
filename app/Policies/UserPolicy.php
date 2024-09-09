<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function createUser(User $user)
    {
        return $user->type=='admin';
    }
    public function editUser(User $user)
    {
        return $user->type=='admin';
    }
    public function deleteUser(User $user)
    {
        return $user->type=='admin';
    }
}
