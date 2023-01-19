<?php

namespace App\Filters\User;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class UserUpdate implements FilterInterface
{
    #[OA\Schema(
        schema: "RequestUpdateUser",
        properties: [
            new OA\Property(property: "password", type: "string", example: "password123"),
            new OA\Property(property: "first_name", type: "string", example: "Bob"),
            new OA\Property(property: "last_name", type: "string", example: "Marley"),
            new OA\Property(property: "role", type: "string", enum: ["admin", "user"]),
        ],
        type: "object"
    )]
    public function before(RequestInterface $request, $arguments = null)
    {
        $segments = $request->getUri()->getSegments();
        $id = array_pop($segments);
        if ($request->getMethod() === 'put' || $request->getMethod() === 'patch') {
            $validator = Services::validation();
            $rules = [
                'first_name' => 'min_length[3]|max_length[100]|alpha',
                'last_name' => 'min_length[3]|max_length[100]|alpha',
                'email' => ['valid_email', "is_unique[users.email,id,{$id}]"],
                'password' => 'alpha_numeric_punct|min_length[3]|max_length[80]',
                'role' => 'min_length[3]|max_length[50]|alpha'
            ];
            $currentRules = array_intersect_key($rules, $request->getJSON(true));

            if (!$validator->validate($currentRules, $request)) {
                return Services::response()->setStatusCode(403)->setJSON([
                    'errors' => $validator->getErrors()
                ]);
            }

            $validCred = $validator->validated();
            $request->setBody(json_encode($validCred));
        }
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
