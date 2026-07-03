<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'company_name'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'subdomain'     => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => false],
            'custom_domain' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Pending','Active','Suspended'], 'default' => 'Active'],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('subdomain');
        $this->forge->createTable('tenants');
    }

    public function down()
    {
        $this->forge->dropTable('tenants');
    }
}
