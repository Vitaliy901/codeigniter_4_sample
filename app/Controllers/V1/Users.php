<?php

namespace App\Controllers\v1;

use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';

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

    public function create()
    {
        $credetials = $this->request->getJSON(true);
        $credetials['password'] = password_hash($credetials['password'], PASSWORD_DEFAULT);
        $id = $this->model->insert($credetials);

        return $this->respond(['user' => $this->model->find($id)]);
    }

    public function show($id = null)
    {
        return $this->respond(['user' => $this->model->find($id)]);
    }

    public function update($id = null)
    {
        $credetials = $this->request->getJSON(true);
        !isset($credetials['password']) ?: $credetials['password'] = password_hash($credetials['password'], PASSWORD_BCRYPT);

        $this->model->update($id, $credetials);
        return $this->respond(['user' => $this->model->find($id)]);
    }

    public function delete($id = null)
    {
        return $this->response->setStatusCode(200)->setJSON(['user' => $id]);
    }
}
