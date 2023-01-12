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
        service('authorization')->authorize('index', Member::class);
        $per_page = $this->request->getGet('per_page');
        $userModel = model(UserModel::class);
        $memberModel = service('authManager')->auth()->role === UserRoles::ADMIN  ?  $this->model : $this->model->where('team_id', $userModel->member->id);

        $data = [
            'data' => [
                'list' => $memberModel->paginate($per_page)
            ],
            'total' => $memberModel->pager->getTotal(),
            'page' => $memberModel->pager->getCurrentPage(),
            'per_page' => $memberModel->pager->getPerPage(),
            'total_pages' => $memberModel->pager->getPageCount(),
        ];
        return $this->respond($data);
    }

    public function create()
    {
        // to do...
        service('authorization')->authorize('create', Member::class);
        $credentials = $this->request->getJSON(true);
        $id = $this->model->insert($credentials);

        return $this->respond(['team' => $this->model->find($id)]);
    }

    public function show($id = null)
    {
        // to do...
        service('authorization')->authorize('show', Member::class);
        return $this->respond(['teamMember' => $this->model->find($id)]);
    }

    public function update($id = null)
    {
        service('authorization')->authorize('update', Member::class);
        // to do...
    }

    public function delete($id = null)
    {
        // to do...
        service('authorization')->authorize('delete', Member::class);
        return $this->response->setStatusCode(200)->setJSON(['teamMember' => $id]);
    }
}
