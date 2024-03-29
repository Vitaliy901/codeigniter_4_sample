<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use Config\Services as AppServices;
use Config\Validation as ValidationConfig;
use App\Modifications\Validation;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function authManager(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('authManager');
        }

        return new \App\BaseServices\AuthManager;
    }

    public static function authorization(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('authorization');
        }

        return new \App\Config\Authorization;
    }

    public static function validation(?ValidationConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('validation', $config);
        }

        $config ??= config('Validation');

        return new Validation($config, AppServices::renderer());
    }
}
