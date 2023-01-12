<?php

namespace App\Policy;

use App\Constants\UserRoles;
use App\Entities\Team;
use App\Entities\User;
use App\Models\UserModel;

class TeamPolicy
{
    public function index(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function create(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }
    public function show(User $user, Team $team)
    {
        $member = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || (!empty($member) && $member->role === UserRoles::HEAD && $team->id === $member->team_id);
    }

    public function update(User $user, Team $team)
    {
        $member = model(UserModel::class)->getMemberByUser($user->id);
        return $user->role === UserRoles::ADMIN || (!empty($member) && $member->role === UserRoles::HEAD && $team->id === $member->team_id);
    }

    public function delete(User $user)
    {
        return $user->role === UserRoles::ADMIN;
    }
}
