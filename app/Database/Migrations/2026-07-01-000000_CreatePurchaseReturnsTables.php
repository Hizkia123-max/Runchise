<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseReturnsTables extends Migration
{
    public function up()
    {
        // Purchase Returns
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_order_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'return_number'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'total_refunded' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'notes'          => ['type' => 'TEXT', 'null' => true],
            'created_by'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['tenant_id', 'return_number']);
        $this->forge->addKey(['purchase_order_id']);
        $this->forge->createTable('purchase_returns', true);

        // Purchase Return Items
        $this->forge->addField([
            'id'                     => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'              => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_return_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_order_item_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'             => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity'               => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'refund_amount'          => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'reason'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'deducted_from_stock'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'             => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'             => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['purchase_return_id']);
        $this->forge->addKey(['purchase_order_item_id']);
        $this->forge->createTable('purchase_return_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_return_items', true);
        $this->forge->dropTable('purchase_returns', true);
    }
}
