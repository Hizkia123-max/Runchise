<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Seed default tenant
        $this->db->table('tenants')->insert([
            'company_name' => 'Runchise Demo Store',
            'subdomain'    => 'demo',
            'status'       => 'Active',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
        $tenantId = $this->db->insertID();

        // Seed default admin user
        $email = getenv('admin.email') ?: 'admin@runchise.com';
        $password = getenv('admin.password') ?: 'Admin@12345';

        $this->db->table('users')->insert([
            'tenant_id'     => $tenantId,
            'name'          => 'Admin Runchise',
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => 'TenantOwner',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // Seed default branch
        $this->db->table('branches')->insert([
            'tenant_id'  => $tenantId,
            'name'       => 'Main Branch',
            'address'    => 'Jl. Sudirman No. 1, Jakarta Pusat',
            'phone'      => '021-12345678',
            'latitude'   => -6.2088,
            'longitude'  => 106.8456,
            'geo_radius' => 50,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $branchId = $this->db->insertID();

        // Seed sample products
        $products = [
            ['sku' => 'PROD-001', 'name' => 'Wireless Mouse',       'price' => 150000, 'cost' => 80000,  'reorder_point' => 5],
            ['sku' => 'PROD-002', 'name' => 'USB-C Cable 2m',       'price' => 45000,  'cost' => 20000,  'reorder_point' => 10],
            ['sku' => 'PROD-003', 'name' => 'Mechanical Keyboard',  'price' => 450000, 'cost' => 250000, 'reorder_point' => 3],
            ['sku' => 'PROD-004', 'name' => 'LED Monitor 24"',      'price' => 1800000,'cost' => 1200000,'reorder_point' => 2],
            ['sku' => 'PROD-005', 'name' => 'HDMI Cable 3m',        'price' => 85000,  'cost' => 40000,  'reorder_point' => 8],
        ];

        foreach ($products as $product) {
            $this->db->table('products')->insert(array_merge($product, [
                'tenant_id'  => $tenantId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]));
            $productId = $this->db->insertID();

            // Seed initial inventory stock
            $this->db->table('inventory_stocks')->insert([
                'tenant_id'  => $tenantId,
                'branch_id'  => $branchId,
                'product_id' => $productId,
                'quantity'   => rand(5, 100),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        echo "✅ Initial data seeded successfully!\n";
        echo "   Login: " . $email . " / " . $password . "\n";
    }
}
