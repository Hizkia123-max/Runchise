<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountingTables extends Migration
{
    public function up()
    {
        // Chart of Accounts
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'code'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'type'       => ['type' => 'ENUM', 'constraint' => ['Asset','Liability','Equity','Revenue','Expense'], 'null' => false],
            'parent_id'  => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('accounting_coa');

        // Journal Entries
        $this->forge->addField([
            'id'               => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'        => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'reference_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'description'      => ['type' => 'TEXT', 'null' => true],
            'posting_date'     => ['type' => 'DATE', 'null' => false],
            'created_at'       => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('journal_entries');

        // Journal Lines
        $this->forge->addField([
            'id'               => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'        => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'journal_entry_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'coa_id'           => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'debit'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'credit'           => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['journal_entry_id']);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('journal_lines');

        // Customers
        $this->forge->addField([
            'id'              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'name'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'membership_tier' => ['type' => 'ENUM', 'constraint' => ['Bronze','Silver','Gold','Platinum'], 'default' => 'Bronze'],
            'loyalty_points'  => ['type' => 'INT', 'default' => 0],
            'total_spent'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'created_at'      => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'      => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('customers');

        // Employees
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'user_id'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'employee_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'identity_card' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => false],
            'salary'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => false, 'default' => 0],
            'hired_at'      => ['type' => 'DATE', 'null' => false],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('employees');

        // Attendance Logs
        $this->forge->addField([
            'id'                  => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'           => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'employee_id'         => ['type' => 'BIGINT', 'unsigned' => true, 'null' => false],
            'clock_in'            => ['type' => 'TIMESTAMP', 'null' => true],
            'clock_out'           => ['type' => 'TIMESTAMP', 'null' => true],
            'gps_latitude_in'     => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'gps_longitude_in'    => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'gps_latitude_out'    => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'gps_longitude_out'   => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'employee_id']);
        $this->forge->createTable('attendance_logs');

        // Audit Logs (append-only)
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'user_id'       => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'ip_address'    => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'action'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'target_table'  => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'target_id'     => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'payload'       => ['type' => 'JSON', 'null' => true],
            'created_at'    => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id']);
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        foreach (['audit_logs','attendance_logs','employees','customers','journal_lines','journal_entries','accounting_coa'] as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
