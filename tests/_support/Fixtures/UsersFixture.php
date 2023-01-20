<?php

namespace Tests\Support\Fixtures;

use App\Constants\UserRoles;

class UsersFixture
{
    private static function schema(): array {
        return [
            'email' => 'test@gmail.com',
            'password' => '12345678',
            'first_name' => 'Name',
            'last_name' => 'LastName',
            'role' => UserRoles::USER,
        ];
    }

    public static function registerUser(): array {
        return array_merge(self::schema(), [
            'first_name' => 'Bob',
            'last_name' => 'Marley',
            'email' => 'Bob@gmail.com',
            'password' => 'password123',
            'role' => UserRoles::USER,
        ]);
    }
    public static function loginUser(): array {
        return array_intersect_key(self::registerUser(), [
            'email' => 'Bob@gmail.com',
            'password' => 'password123',
        ]);
    }
}