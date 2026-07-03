<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
