<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Teams extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ])
            ->addPrimaryKey('id', TRUE)
            ->createTable('teams');
    }

    public function down()
    {
        $this->forge->dropTable('teams');
    }
}
