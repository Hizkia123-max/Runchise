<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSaaSBillingTables extends Migration
{
    public function up()
    {
        // SaaS Plans
        $this->forge->addField([
            'id'                         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'name'                       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'price_monthly'              => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false],
            'max_branches'               => ['type' => 'INT', 'null' => false, 'default' => 1],
            'max_users'                  => ['type' => 'INT', 'null' => false, 'default' => 3],
            'max_monthly_transactions'   => ['type' => 'INT', 'null' => false, 'default' => 1000],
            'has_accounting'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'has_hr'                     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'                 => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'                 => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('saas_plans');

        // Subscriptions
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'plan_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'status'      => ['type' => 'ENUM', 'constraint' => ['Trial','Active','GracePeriod','Suspended'], 'default' => 'Trial'],
            'starts_at'   => ['type' => 'TIMESTAMP', 'null' => false],
            'expires_at'  => ['type' => 'TIMESTAMP', 'null' => false],
            'auto_renew'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('saas_subscriptions');
    }

    public function down()
    {
        $this->forge->dropTable('saas_subscriptions', true);
        $this->forge->dropTable('saas_plans', true);
    }
}
