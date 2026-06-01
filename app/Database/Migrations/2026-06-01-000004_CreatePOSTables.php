<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePOSTransactionsTables extends Migration
{
    public function up()
    {
        // POS Sessions
        $this->forge->addField([
            'id'           => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'    => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'    => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'user_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'opening_cash' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'closing_cash' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'status'       => ['type' => 'ENUM', 'constraint' => ['Open','Closed'], 'default' => 'Open'],
            'opened_at'    => ['type' => 'TIMESTAMP', 'null' => true],
            'closed_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id','branch_id']);
        $this->forge->createTable('pos_sessions');

        // Transactions
        $this->forge->addField([
            'id'              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'pos_session_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'customer_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'invoice_number'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'subtotal'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'discount_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'tax_amount'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'total'           => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'payment_method'  => ['type' => 'ENUM', 'constraint' => ['Cash','QRIS','Card','Split'], 'default' => 'Cash'],
            'payment_status'  => ['type' => 'ENUM', 'constraint' => ['Unpaid','Paid','Refunded'], 'default' => 'Unpaid'],
            'created_at'      => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'      => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('invoice_number');
        $this->forge->addKey(['tenant_id','branch_id']);
        $this->forge->createTable('transactions');

        // Transaction Items
        $this->forge->addField([
            'id'              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'transaction_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'unit_price'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'discount_amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'tax_amount'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'total'           => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['transaction_id']);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('transaction_items');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_items');
        $this->forge->dropTable('transactions');
        $this->forge->dropTable('pos_sessions');
    }
}
