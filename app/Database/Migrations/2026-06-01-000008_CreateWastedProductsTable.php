<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWastedProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity'    => ['type' => 'INT', 'null' => false],
            'cost_price'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'reason'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('product_id');
        $this->forge->createTable('wasted_products');
    }

    public function down()
    {
        $this->forge->dropTable('wasted_products');
    }
}
