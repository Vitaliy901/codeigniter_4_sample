<?php

namespace App\Policy;

use App\Constants\UserRoles;
use App\Entities\User;

class UserPolicy
{
    public function main(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }
}
