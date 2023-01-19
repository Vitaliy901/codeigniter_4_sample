<?php

namespace App\Controllers\V1;

use App\Constants\UserRoles;
use App\Entities\Member;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use OpenApi\Attributes as OA;

class Members extends ResourceController
{
    protected $modelName = 'App\Models\MemberModel';
    protected $format = 'json';

    #[OA\Get(
        path: "api/v1/members",
        operationId: "membersListing",
        summary: "Get list of members",
        security: [["bearerAuth" => []]],
        tags: ["members"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/Locale"),
            new OA\Parameter(ref: "#/components/parameters/Pagination_page"),
            new OA\Parameter(ref: "#/components/parameters/Pagination_per_page"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/200", response: 200),
        ]
    )]
    public function index()
    {
        $user = service('authManager')->auth();
        $userMember = model(UserModel::class)->getMemberByUser($user->id);
        $builder = $user->role === UserRoles::ADMIN ?
            $this->model : $this->model->where('team_id', $userMember->team_id);

        $per_page = $this->request->getGet('per_page');

        $data = [
            'data' => [
                'list' => $builder->paginate($per_page)
            ],
            'total' => $builder->pager->getTotal(),
            'page' => $builder->pager->getCurrentPage(),
            'per_page' => $builder->pager->getPerPage(),
            'total_pages' => $builder->pager->getPageCount(),
        ];
        return $this->respond($data);
    }

    #[OA\Post(
        path: "api/v1/members",
        operationId: "createMember",
        summary: "Create member",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestCreateMember")]),
        tags: ["members"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/200", response: 200),
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function create()
    {
        service('authorization')->authorize('create', Member::class);
        $credentials = $this->request->getJSON(true);
        $user = service('authManager')->auth();

        $db = \Config\Database::connect();
        $db->transStart();
        if ($user->role === UserRoles::ADMIN && $credentials['role'] === UserRoles::HEAD) {
            $this->model->updateRole($credentials['team_id']);
        }
        $id = $this->model->insert($credentials);
        $db->transComplete();
        return $this->respond(['member' => $this->model->find($id)]);
    }

    #[OA\Get(
        path: "api/v1/members/{memberId}",
        operationId: 'showMember',
        summary: "Мember information",
        security: [["bearerAuth" => []]],
        tags: ["members"],
        parameters: [
            new OA\Parameter(name: "memberId", description: "Member ID", in: "path", required: true, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/200", response: 200),
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/404", response: 404),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function show($id = null)
    {
        $user = service('authManager')->auth();
        $userMember = model(UserModel::class)->getMemberByUser($user->id);
        $member = $user->role === UserRoles::ADMIN ?
            $this->model->find($id) : $this->model->where('team_id', $userMember->team_id)->where('id', $id)->first();

        return $this->respond(['member' => $member]);
    }

    #[OA\Patch(
        path: "api/v1/members/change_role/{memberId}",
        operationId: 'updateMember',
        summary: "Update member",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestUpdateMember")]),
        tags: ["members"],
        parameters: [
            new OA\Parameter(name: "memberId", description: "Member ID", in: "path", required: true, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/200", response: 200),
            new OA\Response(ref: "#/components/responses/401", response: 401),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/404", response: 404),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function update($id = null)
    {
        $requestedMember = $this->model->find($id);
        service('authorization')->authorize('update', $requestedMember);
        $credentials = $this->request->getJSON(true);

        $db = \Config\Database::connect();
        $db->transStart();
        if ($credentials['role'] === UserRoles::HEAD) {
            $this->model->updateRole($requestedMember->team_id);
        }
        $this->model->update($id, $credentials);
        $db->transComplete();

        return $this->respond(['member' => $this->model->find($id)]);
    }

    #[OA\Delete(
        path: "api/v1/members/{memberId}",
        operationId: 'deleteMember',
        summary: "Delete member",
        security: [["bearerAuth" => []]],
        tags: ["members"],
        parameters: [
            new OA\Parameter(name: "memberId", description: "Member ID", in: "path", required: true, schema: new OA\Schema(type: "integer")),
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
        $requestedMember = $this->model->find($id);
        service('authorization')->authorize('delete', $requestedMember);

        $this->model->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'member' => [],
            'status' => 'Success',
        ]);
    }
}
