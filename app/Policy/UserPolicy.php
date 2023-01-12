<?php

namespace App\Policy;

use App\Constants\UserRoles;
use App\Entities\User;

class UserPolicy
{
    public function create(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function update(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function delete(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }
}
