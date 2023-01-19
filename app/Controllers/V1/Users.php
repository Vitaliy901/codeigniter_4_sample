<?php

namespace App\Controllers\V1;

use App\Entities\User;
use CodeIgniter\RESTful\ResourceController;
use OpenApi\Attributes as OA;

class Users extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';

    public function __construct()
    {
        service('authorization')->authorize('main', User::class);
    }

    #[OA\Get(
        path: "api/v1/admin/users",
        operationId: "usersListing",
        summary: "Get list of users",
        security: [["bearerAuth" => []]],
        tags: ["users"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/Locale"),
            new OA\Parameter(ref: "#/components/parameters/Pagination_page"),
            new OA\Parameter(ref: "#/components/parameters/Pagination_per_page"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function index()
    {
        $per_page = $this->request->getGet('per_page');
        $user = $this->model;

        $data = [
            'data' => [
                'list' => $this->model->paginate($per_page)
            ],
            'total' => $user->pager->getTotal(),
            'page' => $user->pager->getCurrentPage(),
            'per_page' => $user->pager->getPerPage(),
            'total_pages' => $user->pager->getPageCount(),
        ];
        return $this->respond($data);
    }

    #[OA\Post(
        path: "api/v1/admin/users",
        operationId: "createUser",
        summary: "Create user",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestCreateUser"),
        ]),
        tags: ["users"],
        parameters: [new OA\Parameter(ref: "#/components/parameters/Locale")],
        responses: [
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function create()
    {
        $credentials = $this->request->getJSON(true);
        $credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);
        $id = $this->model->insert($credentials);

        return $this->respond(['user' => $this->model->find($id)]);
    }

    #[OA\Get(
        path: "api/v1/admin/users/{userId}",
        operationId: "showUser",
        summary: "User information",
        security: [["bearerAuth" => []]],
        tags: ["users"],
        parameters: [
            new OA\Parameter(
                name: "userId",
                description: "User ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function show($id = null)
    {
        return $this->respond(['user' => $this->model->find($id)]);
    }

    #[OA\Patch(
        path: "api/v1/admin/users/{userId}",
        operationId: "updateUser",
        summary: "Update user",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestUpdateUser"),
        ]),
        tags: ["users"],
        parameters: [
            new OA\Parameter(
                name: "userId",
                description: "User ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/404", response: 404),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function update($id = null)
    {
        $credentials = $this->request->getJSON(true);
        !isset($credentials['password']) ?: $credentials['password'] = password_hash($credentials['password'], PASSWORD_BCRYPT);

        $this->model->update($id, $credentials);
        return $this->respond(['user' => $this->model->find($id)]);
    }

    #[OA\Delete(
        path: "api/v1/admin/users/{userId}",
        operationId: "deleteUser",
        summary: "Delete user",
        security: [["bearerAuth" => []]],
        tags: ["users"],
        parameters: [
            new OA\Parameter(
                name: "userId",
                description: "User ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/404", response: 404),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'user' => [],
            'status' => 'Success',
        ]);
    }
}
