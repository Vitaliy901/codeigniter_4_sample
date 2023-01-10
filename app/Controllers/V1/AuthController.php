<?php

namespace App\Controllers\V1;

use App\Controllers\BaseController;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    public function register()
    {
        $credentials = $this->request->getJSON(true);
        $credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);
        $user = model(User::class);

        $id = $user->insert($credentials);

        return $this->response->setJSON([
            'user' =>  $user->find($id),
            'status' => 'Success',
        ]);
    }
    public function login()
    {
        $credentials = $this->request->getJSON(true);
        $userModel = model(User::class);
        $user = $userModel->where('email', $credentials['email'])->first();

        if ($user) {
            if (password_verify($credentials['password'], $user->password)) {
                $key =  getenv('jwt_key');
                $payload = [
                    'iss' => getenv('app_baseURL'),
                    'exp' => time() + 60 * 60 * 24 * 7,
                    'id' => $user->id,
                    'role' => $user->role,
                ];
                $jwt = JWT::encode($payload, $key, 'HS256');

                return $this->response->setJSON([
                    'token' =>  $jwt,
                    'status' => 'Success',
                ]);
            }
        }
        return $this->response->setJSON([
            'user' =>  [],
            'status' => 'Not found',
        ])->setStatusCode(404);
    }

    public function logout()
    {
    }
}
