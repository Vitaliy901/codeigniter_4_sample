<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Team extends Entity
{
    protected $attributes = [
        'name',
        'url',
    ];

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
