<?php

namespace App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;

class Teams extends ResourceController
{
    protected $modelName = 'App\Models\TeamModel';
    protected $format = 'json';

    public function index()
    {
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

    public function create()
    {
        $credetials = $this->request->getJSON(true);
        $id = $this->model->insert($credetials);

        return $this->respond(['team' => $this->model->find($id)]);
    }

    public function show($id = null)
    {
        return $this->respond(['team' => $this->model->find($id)]);
    }

    public function update($id = null)
    {
        $credetials = $this->request->getJSON(true);
        $this->model->update($id, $credetials);
        return $this->respond(['team' => $this->model->find($id)]);
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'team' => [],
            'status' => 'Success',
        ]);
    }
}
