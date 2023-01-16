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
        $member = model(UserModel::class)->getMemberByUser($user->id);
        $builder = $user->role === UserRoles::ADMIN ?
            $this->model : $this->model->where('team_id', $member->team_id);

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
        // Frozen...

        return $this->respond(['member' => $this->model->find($id)]);
    }

    public function show($id = null)
    {
        $member = $this->model->find($id);
        service('authorization')->authorize('show', $member);
        return $this->respond(['member' => $member]);
    }

    public function update($id = null)
    {
        // Frozen...
    }

    public function delete($id = null)
    {
        service('authorization')->authorize('delete', Member::class);

        $this->model->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'member' => [],
            'status' => 'Success',
        ]);
    }
}
