<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    /**
     * Determine whether the user can view any models.
     */


    /**
     * Determine whether the user can create models.
     */

     public function createClient(User $user, Client $client=null )
    {
        return $user->type == 'admin';
    }
    public function editClient(User $user,Client $client)
    {
        return $user->type == 'admin';

    }
    public function deleteClient(User $user,Client $client)
    {
        return $user->type == 'admin';

    }
    public function restoreClient(User $user,Client $client)
    {
        return $user->type == 'admin';

    }


}
