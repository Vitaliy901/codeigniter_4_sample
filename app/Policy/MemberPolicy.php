<?php

namespace App\Policy;

use App\Constants\UserRoles;
use App\Entities\Member;
use App\Entities\User;
use App\Models\UserModel;

class MemberPolicy
{
    public function index(User $user)
    {
        $member = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || $member->role === UserRoles::HEAD;
    }

    public function create(User $user)
    {
        // to do...
        return $user->role === UserRoles::ADMIN;
    }
    public function show(User $user, Member $member)
    {
        // to do...
    }

    public function update(User $user, Member $member)
    {
        // to do...
    }

    public function delete(User $user)
    {
        // to do...
        return $user->role === UserRoles::ADMIN;
    }
}
