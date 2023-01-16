<?php

namespace App\Policy;

use App\Constants\UserRoles;
use App\Entities\Member;
use App\Entities\User;

class MemberPolicy
{
    public function create(User $user)
    {
        $userMember = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || (!empty($userMember) && $userMember->role === UserRoles::HEAD);
    }

    public function update(User $user, Member $member)
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function delete(User $user, Member $member)
    {
        $userMember  = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || (!empty($userMember ) && $userMember->role === UserRoles::HEAD);
    }
}
