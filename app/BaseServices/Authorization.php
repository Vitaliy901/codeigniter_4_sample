<?php

namespace App\BaseServices;

use App\Entities\User;
use CodeIgniter\Entity\Entity;
use CodeIgniter\Exceptions\AlertError;
use CodeIgniter\Exceptions\ModelException;

class Authorization
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected array $policies = [];

    private User $authUser;

    public function __construct()
    {
        $this->authUser = service('authManager')->auth();
    }
    public function authorize(string $method, Entity|string $model)
    {
        $className = is_object($model) ? get_class($model) : $model;

        foreach ($this->policies as $key => $value) {
            if ($key === $className) {
                $policy = new $value;
                $policy->{$method}($this->authUser, !is_object($model) ?: $model) ?: throw new AlertError('Unauthorized', 401);
                return;
            }
        }

        throw new ModelException('Model not exists');
    }
}
