<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $attributes = [
        'id'         => null,
        'first_name'  => null, // In the $attributes, the key is the db column name
        'last_name'      => null,
        'email' => null,
        'password'   => null,
        'role' => null,
        'active' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
