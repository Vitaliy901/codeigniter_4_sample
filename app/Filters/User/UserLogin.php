<?php

namespace App\Filters\User;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class UserLogin implements FilterInterface
{
    #[OA\Schema(
        schema: "RequestLogin",
        properties: [
            new OA\Property(property: "email", type: "string", example: "example@gmail.com"),
            new OA\Property(property: "password", type: "string", example: "password123"),
        ],
        type: "object"
    )]
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() === 'post') {
            $validator = Services::validation();
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|alpha_numeric_punct|min_length[3]|max_length[80]',
            ];

            if (!$validator->validate($rules, $request)) {
                return Services::response()->setStatusCode(403)->setJSON([
                    'errors' => $validator->getErrors()
                ]);
            }

            $validCred = $validator->validated();
            $request->setBody(json_encode($validCred));
        }
        return $request;
    }

    #[OA\Schema(
        schema: "ResourceAuthToken",
        properties: [
            new OA\Property(property: "access_token", type: "string"),
        ],
        type: "object"
    )]
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
