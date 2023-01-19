<?php

namespace App\Filters\User;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class UserRegister implements FilterInterface
{
    #[OA\Schema(
        schema: "RequestRegister",
        properties: [
            new OA\Property(property: "email", type: "string", example: "example@gmail.com"),
            new OA\Property(property: "password", type: "string", example: "password123"),
            new OA\Property(property: "first_name", type: "string", example: "Bob"),
            new OA\Property(property: "last_name", type: "string", example: "Marley"),
        ],
        type: "object"
    )]
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() === 'post') {
            $validator = Services::validation();
            $rules = [
                'first_name' => 'required|min_length[3]|max_length[100]|alpha',
                'last_name' => 'required|min_length[3]|max_length[100]|alpha',
                'email' => 'required|valid_email|is_unique[users.email]',
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

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
