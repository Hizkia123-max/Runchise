<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBranchesAndWarehousesTable extends Migration
{
    public function up()
    {
        // Branches
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'address'    => ['type' => 'TEXT', 'null' => true],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'latitude'   => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'longitude'  => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'geo_radius' => ['type' => 'INT', 'default' => 50],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('branches');

        // Products
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'sku'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'barcode'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'price'         => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false, 'default' => 0],
            'cost'          => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false, 'default' => 0],
            'reorder_point' => ['type' => 'INT', 'default' => 10],
            'category_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'brand_id'      => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->addKey(['barcode']);
        $this->forge->addUniqueKey(['tenant_id','sku']);
        $this->forge->createTable('products');

        // Inventory Stocks
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'branch_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'product_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'quantity'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id','branch_id']);
        $this->forge->addKey(['product_id']);
        $this->forge->createTable('inventory_stocks');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_stocks');
        $this->forge->dropTable('products');
        $this->forge->dropTable('branches');
    }
}
