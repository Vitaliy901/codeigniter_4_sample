<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Member extends Entity
{
    protected $attributes = [
        'team_id',
        'user_id',
        'role',
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
