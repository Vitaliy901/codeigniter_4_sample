<?php

namespace App\BaseServices;

use App\Entities\User;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;

class AuthManager
{
    private User $user;

    public function __construct()
    {
        $this->getAuthUser();
    }

    private function getAuthUser()
    {
        $token = $this->getToken();
        $key = getenv('jwt_key');
        try {
            $data = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            throw new RuntimeException('Unauthorized ', 401);
        }
        $user = model(UserModel::class)->find($data->id);

        $this->user = ($user && $data->exp > time()) ? $user : null;
    }

    private function getToken(): ?string
    {
        $header = service('request')->getHeader('Authorization');

        if ($header) {
            preg_match('#Bearer (\S+)#', $header, $matches);
            return isset($matches[1]) ? $matches[1] : null;
        }

        throw new RuntimeException('Authorization header is not exists');
    }

    public function auth()
    {
        return $this->user;
    }
}
