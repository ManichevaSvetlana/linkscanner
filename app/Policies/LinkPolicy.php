<?php

namespace App\Policies;

use App\Link;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function update(User $user)
    {
        return true;
    }

    public function delete(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user)
    {
        return true;
    }

    public function userAccess(User $user, Link $link)
    {
        return $link->user_id == $user->id;
    }


}
