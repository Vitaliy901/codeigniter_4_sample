<?php

namespace App\Controllers\V1;

use App\Entities\Team;
use CodeIgniter\RESTful\ResourceController;
use OpenApi\Attributes as OA;

class Teams extends ResourceController
{
    protected $modelName = 'App\Models\TeamModel';
    protected $format = 'json';

    #[OA\Get(
        path: "api/v1/teams",
        operationId: "teamsListing",
        summary: "Get list of teams",
        security: [["bearerAuth" => []]],
        tags: ["teams"],
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
        service('authorization')->authorize('index', Team::class);
        $per_page = $this->request->getGet('per_page');
        $team = $this->model;

        $data = [
            'data' => [
                'list' => $this->model->paginate($per_page)
            ],
            'total' => $team->pager->getTotal(),
            'page' => $team->pager->getCurrentPage(),
            'per_page' => $team->pager->getPerPage(),
            'total_pages' => $team->pager->getPageCount(),
        ];
        return $this->respond($data);
    }

    #[OA\Post(
        path: "api/v1/teams",
        operationId: "createTeam",
        summary: "Create Team",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestCreateTeam")]),
        tags: ["teams"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/Locale"),
        ],
        responses: [
            new OA\Response(ref: "#/components/responses/200", response: 200),
            new OA\Response(ref: "#/components/responses/403", response: 403),
            new OA\Response(ref: "#/components/responses/500", response: 500),
        ]
    )]
    public function create()
    {
        service('authorization')->authorize('create', Team::class);

        $credentials = $this->request->getJSON(true);
        $id = $this->model->insert($credentials);

        return $this->respond(['team' => $this->model->find($id)]);
    }

    #[OA\Get(
        path: "api/v1/teams/{teamId}",
        operationId: 'showTeam',
        summary: "Team information",
        security: [["bearerAuth" => []]],
        tags: ["teams"],
        parameters: [
            new OA\Parameter(name: "teamId", description: "Team ID", in: "path", required: true, schema: new OA\Schema(type: "integer")),
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
        $team = $this->model->find($id);
        service('authorization')->authorize('show', $team);
        return $this->respond(['team' => $team]);
    }

    #[OA\Patch(
        path: "api/v1/teams/{teamId}",
        operationId: 'updateTeam',
        summary: "Update team",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: [
            new OA\JsonContent(ref: "#/components/schemas/RequestUpdateTeam")]),
        tags: ["teams"],
        parameters: [
            new OA\Parameter(name: "teamId", description: "Team ID", in: "path", required: true, schema: new OA\Schema(type: "integer")),
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
        $team = $this->model->find($id);
        service('authorization')->authorize('update', $team);

        $credentials = $this->request->getJSON(true);
        $this->model->update($id, $credentials);
        return $this->respond(['team' => $this->model->find($id)]);
    }

    #[OA\Delete(
        path: "api/v1/teams/{teamId}",
        operationId: 'deleteTeam',
        summary: "Delete team",
        security: [["bearerAuth" => []]],
        tags: ["teams"],
        parameters: [
            new OA\Parameter(name: "teamId", description: "Team ID", in: "path", required: true, schema: new OA\Schema(type: "integer")),
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
        service('authorization')->authorize('create', Team::class);

        $this->model->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'team' => [],
            'status' => 'Success',
        ]);
    }
}
