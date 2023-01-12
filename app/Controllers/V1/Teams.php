<?php

namespace App\Controllers\V1;

use App\Entities\Team;
use CodeIgniter\RESTful\ResourceController;

class Teams extends ResourceController
{
    protected $modelName = 'App\Models\TeamModel';
    protected $format = 'json';

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

    public function create()
    {
        service('authorization')->authorize('create', Team::class);

        $credentials = $this->request->getJSON(true);
        $id = $this->model->insert($credentials);

        return $this->respond(['team' => $this->model->find($id)]);
    }

    public function show($id = null)
    {
        $team = $this->model->find($id);
        service('authorization')->authorize('show', $team);
        return $this->respond(['team' => $team]);
    }

    public function update($id = null)
    {
        $team = $this->model->find($id);
        service('authorization')->authorize('update', $team);

        $credentials = $this->request->getJSON(true);
        $this->model->update($id, $credentials);
        return $this->respond(['team' => $this->model->find($id)]);
    }

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
