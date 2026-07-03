<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'role'          => ['type' => 'ENUM', 'constraint' => ['SuperAdmin','TenantOwner','Manager','Cashier','InventoryStaff','Accountant','HRStaff'], 'default' => 'Cashier'],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'mfa_secret'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'mfa_enabled'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey(['tenant_id']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
