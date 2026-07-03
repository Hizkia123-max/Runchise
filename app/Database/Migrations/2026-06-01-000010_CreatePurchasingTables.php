<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchasingTables extends Migration
{
    public function up()
    {
        // Suppliers
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'contact_person' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'address'    => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('suppliers', true);

        // Purchase Orders
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'supplier_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'po_number'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'order_date'    => ['type' => 'DATE', 'null' => false],
            'expected_date' => ['type' => 'DATE', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Draft','Ordered','Partially Received','Completed','Cancelled'], 'default' => 'Draft'],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'total_amount'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'created_by'    => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['tenant_id', 'po_number']);
        $this->forge->addKey(['supplier_id']);
        $this->forge->addKey(['status']);
        $this->forge->createTable('purchase_orders', true);

        // Purchase Order Items
        $this->forge->addField([
            'id'                => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'         => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_order_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'        => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity_ordered'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'quantity_received' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'unit_cost'         => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'total_cost'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'created_at'        => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'        => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['purchase_order_id']);
        $this->forge->addKey(['product_id']);
        $this->forge->createTable('purchase_order_items', true);

        // Goods Receivings
        $this->forge->addField([
            'id'                => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'         => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'         => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_order_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'gr_number'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'received_date'     => ['type' => 'DATE', 'null' => false],
            'notes'             => ['type' => 'TEXT', 'null' => true],
            'received_by'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at'        => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'        => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['purchase_order_id']);
        $this->forge->addUniqueKey(['tenant_id', 'gr_number']);
        $this->forge->createTable('goods_receivings', true);

        // Goods Receiving Items
        $this->forge->addField([
            'id'                 => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'          => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'goods_receiving_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'purchase_order_item_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'         => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity_received'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'created_at'         => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'         => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['goods_receiving_id']);
        $this->forge->addKey(['product_id']);
        $this->forge->createTable('goods_receiving_items', true);

        // Stock Card Entries (Kartu Stok)
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'entry_date'     => ['type' => 'DATETIME', 'null' => false],
            'type'           => ['type' => 'ENUM', 'constraint' => ['IN','OUT'], 'null' => false],
            'quantity'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'balance_after'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'reference_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'reference_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'reference_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'description'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'branch_id', 'product_id']);
        $this->forge->addKey(['entry_date']);
        $this->forge->addKey(['reference_type', 'reference_id']);
        $this->forge->createTable('stock_card_entries', true);
    }

    public function down()
    {
        $this->forge->dropTable('stock_card_entries', true);
        $this->forge->dropTable('goods_receiving_items', true);
        $this->forge->dropTable('goods_receivings', true);
        $this->forge->dropTable('purchase_order_items', true);
        $this->forge->dropTable('purchase_orders', true);
        $this->forge->dropTable('suppliers', true);
    }
}
