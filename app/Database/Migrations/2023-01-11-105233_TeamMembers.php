<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeamMembers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'team_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => false,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => false,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'default' => 'member'
            ],
        ])
            ->addForeignKey('team_id', 'teams', 'id', '', 'CASCADE')
            ->addForeignKey('user_id', 'users', 'id', '', 'CASCADE')
            ->createTable('team_members');
    }

    public function down()
    {
        $this->forge->dropTable('team_members');
    }
}
