<?php

namespace App\Controllers\V1;

use App\Constants\UserRoles;
use App\Entities\Member;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Members extends ResourceController
{
    protected $modelName = 'App\Models\MemberModel';
    protected $format = 'json';

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

    public function show($id = null)
    {
        $user = service('authManager')->auth();
        $userMember = model(UserModel::class)->getMemberByUser($user->id);
        $member = $user->role === UserRoles::ADMIN ?
            $this->model->find($id) : $this->model->where('team_id', $userMember->team_id)->where('id', $id)->first();

        return $this->respond(['member' => $member]);
    }

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
