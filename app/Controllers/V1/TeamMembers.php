<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class TeamMembers extends ResourceController
{
    protected $modelName = 'App\Models\TeamMemberModel';
    protected $format = 'json';

    public function index()
    {
        $per_page = $this->request->getGet('per_page');
        $teamMember = $this->model;

        $data = [
            'data' => [
                'list' => $this->model->paginate($per_page)
            ],
            'total' => $teamMember->pager->getTotal(),
            'page' => $teamMember->pager->getCurrentPage(),
            'per_page' => $teamMember->pager->getPerPage(),
            'total_pages' => $teamMember->pager->getPageCount(),
        ];
        return $this->respond($data);
    }

    public function create()
    {
        // to do...
    }

    public function show($id = null)
    {
        return $this->respond(['teamMember' => $this->model->find($id)]);
    }

    public function update($id = null)
    {
        // to do...
    }

    public function delete($id = null)
    {
        return $this->response->setStatusCode(200)->setJSON(['teamMember' => $id]);
    }
}
