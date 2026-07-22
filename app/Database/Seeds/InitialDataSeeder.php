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

        // Seed default owner user
        $email = 'owner@runchise.com';
        $password = 'Owner@12345';

        $this->db->table('users')->insert([
            'tenant_id'     => $tenantId,
            'name'          => 'Owner Runchise',
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => 'TenantOwner',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // Seed admin/manager user
        $this->db->table('users')->insert([
            'tenant_id'     => $tenantId,
            'name'          => 'Manager Runchise',
            'email'         => 'admin@runchise.com',
            'password_hash' => password_hash('Admin@12345', PASSWORD_BCRYPT),
            'role'          => 'Manager',
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
        $categories = ['Laptops & PCs', 'Components', 'Peripherals', 'Networking', 'Services'];
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
            // Laptops & PCs
            ['sku' => 'PC-001', 'name' => 'Lenovo ThinkPad X1 Carbon', 'price' => 25000000, 'cost' => 20000000, 'reorder_point' => 2, 'category_id' => $categoryIds['Laptops & PCs']],
            ['sku' => 'PC-002', 'name' => 'Asus ROG Zephyrus G14', 'price' => 30000000, 'cost' => 25000000, 'reorder_point' => 2, 'category_id' => $categoryIds['Laptops & PCs']],
            ['sku' => 'PC-003', 'name' => 'Dell XPS 15', 'price' => 28000000, 'cost' => 23000000, 'reorder_point' => 1, 'category_id' => $categoryIds['Laptops & PCs']],
            ['sku' => 'PC-004', 'name' => 'MacBook Air M2', 'price' => 18000000, 'cost' => 15000000, 'reorder_point' => 3, 'category_id' => $categoryIds['Laptops & PCs']],
            ['sku' => 'PC-005', 'name' => 'Custom Build PC Intel i7', 'price' => 15000000, 'cost' => 12000000, 'reorder_point' => 5, 'category_id' => $categoryIds['Laptops & PCs']],
            
            // Components
            ['sku' => 'CP-001', 'name' => 'NVIDIA RTX 4070 Ti', 'price' => 14000000, 'cost' => 12000000, 'reorder_point' => 2, 'category_id' => $categoryIds['Components']],
            ['sku' => 'CP-002', 'name' => 'AMD Ryzen 7 7800X3D', 'price' => 7000000, 'cost' => 6000000, 'reorder_point' => 5, 'category_id' => $categoryIds['Components']],
            ['sku' => 'CP-003', 'name' => 'Corsair Vengeance 32GB DDR5', 'price' => 2500000, 'cost' => 2000000, 'reorder_point' => 10, 'category_id' => $categoryIds['Components']],
            ['sku' => 'CP-004', 'name' => 'Samsung 990 PRO 2TB NVMe', 'price' => 3500000, 'cost' => 2800000, 'reorder_point' => 8, 'category_id' => $categoryIds['Components']],
            ['sku' => 'CP-005', 'name' => 'Seasonic Focus 850W Gold', 'price' => 2200000, 'cost' => 1800000, 'reorder_point' => 5, 'category_id' => $categoryIds['Components']],
            
            // Peripherals
            ['sku' => 'PR-001', 'name' => 'Logitech G Pro X Superlight', 'price' => 2000000, 'cost' => 1500000, 'reorder_point' => 5, 'category_id' => $categoryIds['Peripherals']],
            ['sku' => 'PR-002', 'name' => 'Keychron Q1 Pro Mechanical', 'price' => 3500000, 'cost' => 2500000, 'reorder_point' => 3, 'category_id' => $categoryIds['Peripherals']],
            ['sku' => 'PR-003', 'name' => 'LG UltraGear 27" 1440p 165Hz', 'price' => 6000000, 'cost' => 5000000, 'reorder_point' => 2, 'category_id' => $categoryIds['Peripherals']],
            ['sku' => 'PR-004', 'name' => 'HyperX Cloud III Gaming Headset', 'price' => 1500000, 'cost' => 1100000, 'reorder_point' => 5, 'category_id' => $categoryIds['Peripherals']],
            ['sku' => 'PR-005', 'name' => 'Elgato Stream Deck MK.2', 'price' => 2800000, 'cost' => 2200000, 'reorder_point' => 3, 'category_id' => $categoryIds['Peripherals']],
            
            // Networking
            ['sku' => 'NW-001', 'name' => 'Asus RT-AX88U Router Wi-Fi 6', 'price' => 4500000, 'cost' => 3500000, 'reorder_point' => 3, 'category_id' => $categoryIds['Networking']],
            ['sku' => 'NW-002', 'name' => 'TP-Link Deco X20 Mesh 3-Pack', 'price' => 3200000, 'cost' => 2500000, 'reorder_point' => 4, 'category_id' => $categoryIds['Networking']],
            ['sku' => 'NW-003', 'name' => 'Ubiquiti UniFi AP AC Pro', 'price' => 2500000, 'cost' => 2000000, 'reorder_point' => 5, 'category_id' => $categoryIds['Networking']],
            ['sku' => 'NW-004', 'name' => 'Kabel LAN Cat6 Belden 305m', 'price' => 2000000, 'cost' => 1500000, 'reorder_point' => 2, 'category_id' => $categoryIds['Networking']],
            ['sku' => 'NW-005', 'name' => 'Switch Hub Gigabit 16-Port', 'price' => 800000, 'cost' => 600000, 'reorder_point' => 5, 'category_id' => $categoryIds['Networking']],
            
            // Services
            ['sku' => 'SV-001', 'name' => 'Instalasi Windows & Office', 'price' => 150000, 'cost' => 0, 'reorder_point' => 0, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-002', 'name' => 'Jasa Perakitan PC', 'price' => 300000, 'cost' => 0, 'reorder_point' => 0, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-003', 'name' => 'Cleaning & Ganti Thermal Paste', 'price' => 200000, 'cost' => 20000, 'reorder_point' => 0, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-004', 'name' => 'Data Recovery Ringan', 'price' => 500000, 'cost' => 0, 'reorder_point' => 0, 'category_id' => $categoryIds['Services']],
            ['sku' => 'SV-005', 'name' => 'Pemasangan Jaringan LAN (titik)', 'price' => 100000, 'cost' => 20000, 'reorder_point' => 0, 'category_id' => $categoryIds['Services']],
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

        // --- PURCHASING DATA SEEDER ---
        // 1. Seed Supplier
        $this->db->table('suppliers')->insert([
            'tenant_id'      => $tenantId,
            'name'           => 'PT. Indokomputer Jaya',
            'contact_person' => 'Bapak Budi',
            'phone'          => '081234567890',
            'email'          => 'budi@indokomputer.com',
            'address'        => 'Mangga Dua Mall Lt. 3, Jakarta',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);
        $supplierId = $this->db->insertID();

        // 2. Seed Purchase Order
        $this->db->table('purchase_orders')->insert([
            'tenant_id'     => $tenantId,
            'branch_id'     => $branchId,
            'supplier_id'   => $supplierId,
            'po_number'     => 'PO-00001',
            'order_date'    => date('Y-m-d', strtotime('-5 days')),
            'expected_date' => date('Y-m-d', strtotime('-2 days')),
            'status'        => 'Completed',
            'notes'         => 'Pembelian stok awal bulan',
            'total_amount'  => 45000000,
            'created_by'    => 1,
            'created_at'    => date('Y-m-d H:i:s', strtotime('-5 days')),
            'updated_at'    => date('Y-m-d H:i:s', strtotime('-5 days')),
        ]);
        $poId = $this->db->insertID();

        // Add 2 PO Items
        $poItems = [
            ['product_id' => $products[0]['id'] ?? 1, 'qty' => 2, 'cost' => 20000000], // Lenovo ThinkPad
            ['product_id' => $products[11]['id'] ?? 12, 'qty' => 2, 'cost' => 2500000],  // Keychron Q1 Pro
        ];

        foreach ($poItems as $item) {
            $this->db->table('purchase_order_items')->insert([
                'tenant_id'         => $tenantId,
                'purchase_order_id' => $poId,
                'product_id'        => $item['product_id'],
                'quantity_ordered'  => $item['qty'],
                'quantity_received' => $item['qty'],
                'unit_cost'         => $item['cost'],
                'total_cost'        => $item['qty'] * $item['cost'],
                'created_at'        => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at'        => date('Y-m-d H:i:s', strtotime('-5 days')),
            ]);
        }

        if (ENVIRONMENT !== 'testing') {
            echo "✅ Initial data seeded successfully!\n";
            echo "   Login: " . $email . " / " . $password . "\n";
        }
    }
}
