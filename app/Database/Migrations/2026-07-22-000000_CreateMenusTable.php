<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenusTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'parent_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'order_index' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'roles' => [
                'type' => 'TEXT', // JSON array of roles, e.g. ["SuperAdmin", "TenantOwner", "Manager"]
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('menus', true);
    }

    public function down()
    {
        $this->forge->dropTable('menus', true);
    }
}
