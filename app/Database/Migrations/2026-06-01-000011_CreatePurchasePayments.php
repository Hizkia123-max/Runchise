<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchasePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'          => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_order_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'amount'             => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'payment_date'       => ['type' => 'DATE', 'null' => false],
            'payment_method'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_proof_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'notes'              => ['type' => 'TEXT', 'null' => true],
            'created_by'         => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at'         => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'         => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->addKey(['purchase_order_id']);
        $this->forge->createTable('purchase_payments', true);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_payments', true);
    }
}
