<?php

namespace App\Controllers\V1;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    #[OA\Post(
        path: "/api/v1/auth/register",
        operationId: "register",
        summary: "Register user",
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestRegister"),
        ]),
        tags: ["auth"],
        parameters: [new OA\Parameter(ref: "#/components/parameters/Locale")],
        responses: [
            new OA\Response(response: 200, description: "", content: [
                new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string"),
                        new OA\Property(property: "data", ref: "#/components/schemas/ResourceSuccess"),
                    ]
                ),
            ]),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function register()
    {
        $credentials = $this->request->getJSON(true);
        $credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);
        $userModel = model(UserModel::class);

        $id = $userModel->insert($credentials);

        return $this->response->setJSON([
            'user' =>  $userModel->find($id),
            'status' => 'Success',
        ]);
    }
    #[OA\Post(
        path: "/api/v1/auth/login",
        operationId: "login",
        summary: "Login user",
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestLogin"),
        ]),
        tags: ["auth"],
        parameters: [new OA\Parameter(ref: "#/components/parameters/Locale")],
        responses: [
            new OA\Response(response: 200, description: "", content: [
                new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string"),
                        new OA\Property(property: "data", ref: "#/components/schemas/ResourceAuthToken"),
                    ]
                ),
            ]),
            new OA\Response(ref: "#/components/responses/404", response: 404),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function login()
    {
        $credentials = $this->request->getJSON(true);
        $userModel = model(UserModel::class);
        $user = $userModel->where('email', $credentials['email'])->first();

        if ($user) {
            if (password_verify($credentials['password'], $user->password)) {
                $key =  getenv('jwt_key');
                $payload = [
                    'iss' => getenv('app_baseURL'),
                    'exp' => time() + 60 * 60 * 24 * 7,
                    'id' => $user->id
                ];
                $jwt = JWT::encode($payload, $key, 'HS256');

                return $this->response->setJSON([
                    'access_token' =>  $jwt,
                    'status' => 'Success',
                ]);
            }
        }
        return $this->response->setJSON([
            'user' =>  [],
            'status' => 'Not found',
        ])->setStatusCode(404);
    }
}
