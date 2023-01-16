<?php

namespace App\Policy;

use App\Constants\UserRoles;
use App\Entities\Member;
use App\Entities\User;

class MemberPolicy
{

    public function create(User $user)
    {
        $member = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || (!empty($member) && $member->role === UserRoles::HEAD);
    }

    public function show(User $user, Member $member)
    {
        $userMember = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN ||
            !empty($userMember) && $userMember->team_id === $member->team_id;
    }

    public function update(User $user, Member $member)
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function delete(User $user, Member $member)
    {
        $member = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || (!empty($member) && $member->role === UserRoles::HEAD);
    }
}
