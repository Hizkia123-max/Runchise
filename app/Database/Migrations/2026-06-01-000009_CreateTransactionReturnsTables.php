<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionReturnsTables extends Migration
{
    public function up()
    {
        // Transaction Returns table
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'transaction_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'total_refunded' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('transaction_id');
        $this->forge->createTable('transaction_returns');

        // Transaction Return Items table
        $this->forge->addField([
            'id'                  => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'           => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'return_id'           => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'transaction_item_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'          => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity'            => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'refund_amount'       => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'reason'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'returned_to_stock'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'          => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'          => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('return_id');
        $this->forge->addKey('transaction_item_id');
        $this->forge->addKey('product_id');
        $this->forge->createTable('transaction_return_items');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_return_items');
        $this->forge->dropTable('transaction_returns');
    }
}
