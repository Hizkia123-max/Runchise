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

        // Seed default categories
        $categories = ['Food & Beverage', 'Retail', 'Electronics', 'Fashion', 'Services'];
        $categoryIds = [];
        foreach ($categories as $cat) {
            $this->db->table('categories')->insert([
                'tenant_id'  => $tenantId,
                'name'       => $cat,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $categoryIds[$cat] = $this->db->insertID();
        }

        // Seed sample products across all categories
        $products = [
            // Food & Beverage
            ['sku' => 'FB-001', 'name' => 'Nasi Goreng Spesial', 'price' => 35000, 'cost' => 15000, 'reorder_point' => 5, 'category_id' => $categoryIds['Food & Beverage']],
            ['sku' => 'FB-002', 'name' => 'Es Teh Manis', 'price' => 8000, 'cost' => 2000, 'reorder_point' => 10, 'category_id' => $categoryIds['Food & Beverage']],
            ['sku' => 'FB-003', 'name' => 'Kopi Susu Aren', 'price' => 22000, 'cost' => 8000, 'reorder_point' => 5, 'category_id' => $categoryIds['Food & Beverage']],
            ['sku' => 'FB-004', 'name' => 'Ayam Bakar Taliwang', 'price' => 45000, 'cost' => 20000, 'reorder_point' => 3, 'category_id' => $categoryIds['Food & Beverage']],
            ['sku' => 'FB-005', 'name' => 'Roti Bakar Cokelat', 'price' => 18000, 'cost' => 7000, 'reorder_point' => 5, 'category_id' => $categoryIds['Food & Beverage']],
            
            // Retail
            ['sku' => 'RT-001', 'name' => 'Sabun Mandi Cair 500ml', 'price' => 32000, 'cost' => 18000, 'reorder_point' => 5, 'category_id' => $categoryIds['Retail']],
            ['sku' => 'RT-002', 'name' => 'Pasta Gigi Herbal', 'price' => 15000, 'cost' => 8000, 'reorder_point' => 10, 'category_id' => $categoryIds['Retail']],
            ['sku' => 'RT-003', 'name' => 'Minyak Goreng 2L', 'price' => 38000, 'cost' => 28000, 'reorder_point' => 5, 'category_id' => $categoryIds['Retail']],
            ['sku' => 'RT-004', 'name' => 'Tissue Wajah 250s', 'price' => 12000, 'cost' => 6000, 'reorder_point' => 8, 'category_id' => $categoryIds['Retail']],
            ['sku' => 'RT-005', 'name' => 'Deterjen Bubuk 1kg', 'price' => 26000, 'cost' => 16000, 'reorder_point' => 5, 'category_id' => $categoryIds['Retail']],
            
            // Electronics
            ['sku' => 'EL-001', 'name' => 'Wireless Mouse', 'price' => 150000, 'cost' => 80000, 'reorder_point' => 5, 'category_id' => $categoryIds['Electronics']],
            ['sku' => 'EL-002', 'name' => 'USB-C Cable 2m', 'price' => 45000, 'cost' => 20000, 'reorder_point' => 10, 'category_id' => $categoryIds['Electronics']],
            ['sku' => 'EL-003', 'name' => 'Mechanical Keyboard', 'price' => 450000, 'cost' => 250000, 'reorder_point' => 3, 'category_id' => $categoryIds['Electronics']],
            ['sku' => 'EL-004', 'name' => 'LED Monitor 24"', 'price' => 1800000, 'cost' => 1200000, 'reorder_point' => 2, 'category_id' => $categoryIds['Electronics']],
            ['sku' => 'EL-005', 'name' => 'Powerbank 10000mAh', 'price' => 195000, 'cost' => 110000, 'reorder_point' => 5, 'category_id' => $categoryIds['Electronics']],
            
            // Fashion
            ['sku' => 'FS-001', 'name' => 'Kemeja Flannel Pria', 'price' => 185000, 'cost' => 95000, 'reorder_point' => 5, 'category_id' => $categoryIds['Fashion']],
            ['sku' => 'FS-002', 'name' => 'Celana Chino Slimfit', 'price' => 220000, 'cost' => 115000, 'reorder_point' => 5, 'category_id' => $categoryIds['Fashion']],
            ['sku' => 'FS-003', 'name' => 'Kaos Polos Cotton 30s', 'price' => 55000, 'cost' => 25000, 'reorder_point' => 10, 'category_id' => $categoryIds['Fashion']],
            ['sku' => 'FS-004', 'name' => 'Jaket Hoodie Fleece', 'price' => 250000, 'cost' => 130000, 'reorder_point' => 3, 'category_id' => $categoryIds['Fashion']],
            ['sku' => 'FS-005', 'name' => 'Rok Plisket Wanita', 'price' => 110000, 'cost' => 55000, 'reorder_point' => 5, 'category_id' => $categoryIds['Fashion']],
            
            // Services
            ['sku' => 'SV-001', 'name' => 'Cuci Sepatu Standard', 'price' => 45000, 'cost' => 10000, 'reorder_point' => 5, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-002', 'name' => 'Potong Rambut Pria + Pijat', 'price' => 65000, 'cost' => 15000, 'reorder_point' => 5, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-003', 'name' => 'Jasa Setrika Premium /kg', 'price' => 12000, 'cost' => 2000, 'reorder_point' => 10, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-004', 'name' => 'Cuci Helm Full Face', 'price' => 35000, 'cost' => 8000, 'reorder_point' => 5, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-005', 'name' => 'Deep Cleaning Sepeda', 'price' => 85000, 'cost' => 20000, 'reorder_point' => 3, 'category_id' => $categoryIds['Services']],
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

        if (ENVIRONMENT !== 'testing') {
            echo "✅ Initial data seeded successfully!\n";
            echo "   Login: " . $email . " / " . $password . "\n";
        }
    }
}
