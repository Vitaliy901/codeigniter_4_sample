<?php

namespace Config;

use App\Filters\Auth;
use App\Filters\Member\MemberCreate;
use App\Filters\Member\MemberUpdate;
use App\Filters\StripTags;
use App\Filters\Team\TeamCreate;
use App\Filters\Team\TeamUpdate;
use App\Filters\Trim;
use App\Filters\User\UserCreate;
use App\Filters\User\UserLogin;
use App\Filters\User\UserRegister;
use App\Filters\User\UserUpdate;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'user_create' => UserCreate::class,
        'user_update' => UserUpdate::class,
        'user_register' => UserRegister::class,
        'user_login' => UserLogin::class,
        'team_create' => TeamCreate::class,
        'team_update' => TeamUpdate::class,
        'member_create' => MemberCreate::class,
        'member_update' => MemberUpdate::class,
        'auth' => Auth::class,
        'baseInputFilter' => [
            Trim::class,
            StripTags::class
        ]
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            'baseInputFilter'
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
        'auth' => [
            'before' => ['api/v1/users', 'api/v1/teams', 'api/v1/members']
        ],
        'user_create' => [
            'before' => ['api/v1/users']
        ],
        'user_update' => [
            'before' => ['api/v1/users/*']
        ],
        'team_create' => [
            'before' => ['api/v1/teams']
        ],
        'team_update' => [
            'before' => ['api/v1/teams/*']
        ],
        'member_create' => [
            'before' => ['api/v1/members']
        ],
        'member_update' => [
            'before' => ['api/v1/members/change_role/*']
        ],
    ];
}
