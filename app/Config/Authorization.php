<?php

namespace App\Config;

use App\BaseServices\Authorization as BaseAuthorization;
use App\Entities\Member;
use App\Entities\Team;
use App\Entities\User;
use App\Policy\MemberPolicy;
use App\Policy\TeamPolicy;
use App\Policy\UserPolicy;

class Authorization extends BaseAuthorization
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected array $policies = [
        User::class => UserPolicy::class,
        Team::class => TeamPolicy::class,
        Member::class => MemberPolicy::class,
    ];
}
