<?php

namespace App\Constants;

class UserRoles
{
    /**
     * @Message ("Member")
     */
    public const MEMBER = 'member';
    /**
     * @Message ("Head")
     */
    public const HEAD = 'head';
    /**
     * @Message ("User")
     */
    public const USER = 'user';

    /**
     * @Message ("Admin")
     */
    public const ADMIN = 'admin';

    /**
     * @Message ("Group of all users type")
     */
    public const GROUP_USERS = [self::USER];

    /**
     * @Message ("Group of all admins type")
     */
    public const GROUP_ADMINS = [self::ADMIN];

    /**
     * @Message ("Group of all users")
     */
    public const GROUP_ALL = [self::USER, self::ADMIN];
}
