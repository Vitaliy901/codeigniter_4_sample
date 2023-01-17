<?php

namespace App\Models;

use App\Constants\UserRoles;
use App\Entities\Member;
use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'team_members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = Member::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'team_id',
        'user_id',
        'role',
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function updateRole(int $team_id) {
        $this->builder->where('team_id', $team_id)
            ->set('role', UserRoles::MEMBER)
            ->update();
    }
}
