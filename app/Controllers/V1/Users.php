<?php

namespace App\Controllers\V1;

use App\Entities\User;
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
        service('authorization')->authorize('create', User::class);

        $credentials = $this->request->getJSON(true);
        $credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);
        $id = $this->model->insert($credentials);

        return $this->respond(['user' => $this->model->find($id)]);
    }

    public function show($id = null)
    {
        return $this->respond(['user' => $this->model->find($id)]);
    }

    public function update($id = null)
    {
        service('authorization')->authorize('update', User::class);

        $credentials = $this->request->getJSON(true);
        !isset($credentials['password']) ?: $credentials['password'] = password_hash($credentials['password'], PASSWORD_BCRYPT);

        $this->model->update($id, $credentials);
        return $this->respond(['user' => $this->model->find($id)]);
    }

    public function delete($id = null)
    {
        service('authorization')->authorize('delete', User::class);
        $this->model->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'user' => [],
            'status' => 'Success',
        ]);
    }
}
