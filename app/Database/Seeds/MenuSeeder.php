<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('menus')->truncate();

        $menus = [
            // Dashboard
            [
                'title' => 'Dashboard Utama',
                'url' => '',
                'icon' => 'bi-grid-1x2',
                'order_index' => 1,
                'is_active' => 1,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'Ringkasan Bisnis', 'url' => 'dashboard', 'icon' => 'bi-bar-chart-line', 'order_index' => 1],
                    ['title' => 'Aktivitas Hari Ini', 'url' => 'dashboard/activity', 'icon' => 'bi-activity', 'order_index' => 2],
                ]
            ],
            // Kasir & Transaksi
            [
                'title' => 'Kasir & Transaksi',
                'url' => null,
                'icon' => 'bi-pc-display-horizontal',
                'order_index' => 2,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'POS Terminal', 'url' => 'pos/terminal', 'icon' => 'bi-calculator', 'order_index' => 1],
                    ['title' => 'Shift Kasir', 'url' => 'pos/sessions', 'icon' => 'bi-clock-history', 'order_index' => 2],
                    ['title' => 'Riwayat Transaksi', 'url' => 'pos/history', 'icon' => 'bi-receipt', 'order_index' => 3],
                    ['title' => 'Retur Penjualan', 'url' => 'pos/returns', 'icon' => 'bi-arrow-counterclockwise', 'order_index' => 4],
                ]
            ],
            // Pembelian
            [
                'title' => 'Pembelian',
                'url' => null,
                'icon' => 'bi-cart-check-fill',
                'order_index' => 3,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'Purchase Order (PO)', 'url' => 'purchasing/orders', 'icon' => 'bi-file-earmark-text', 'order_index' => 1],
                    ['title' => 'Penerimaan Barang', 'url' => 'purchasing/receivings', 'icon' => 'bi-box-arrow-in-down', 'order_index' => 2],
                    ['title' => 'Retur Pembelian', 'url' => 'purchasing/returns', 'icon' => 'bi-arrow-counterclockwise', 'order_index' => 3],
                    ['title' => 'Daftar Supplier', 'url' => 'purchasing/suppliers', 'icon' => 'bi-people', 'order_index' => 4],
                ]
            ],
            // Katalog
            [
                'title' => 'Katalog Produk',
                'url' => null,
                'icon' => 'bi-box-seam-fill',
                'order_index' => 4,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'Daftar Produk', 'url' => 'inventory/products', 'icon' => 'bi-box', 'order_index' => 1],
                    ['title' => 'Kategori Produk', 'url' => 'inventory/categories', 'icon' => 'bi-tags', 'order_index' => 2],
                    ['title' => 'Diskon & Promo', 'url' => 'inventory/promos', 'icon' => 'bi-percent', 'order_index' => 3],
                ]
            ],
            // Stok
            [
                'title' => 'Stok & Inventori',
                'url' => null,
                'icon' => 'bi-graph-up-arrow',
                'order_index' => 5,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'Stok Saat Ini', 'url' => 'report/stock-onhand', 'icon' => 'bi-box-seam', 'order_index' => 1],
                    ['title' => 'Stock Opname', 'url' => 'inventory/opname', 'icon' => 'bi-pencil-square', 'order_index' => 2],
                    ['title' => 'Kartu Stok (Mutasi)', 'url' => 'report/stock-card', 'icon' => 'bi-journal-text', 'order_index' => 3],
                    ['title' => 'Transfer Stok', 'url' => 'inventory/transfers', 'icon' => 'bi-arrow-left-right', 'order_index' => 4],
                ]
            ],
            // Laporan
            [
                'title' => 'Laporan',
                'url' => null,
                'icon' => 'bi-journal-check',
                'order_index' => 6,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'Penjualan (Numerik)', 'url' => 'report/sales/numeric', 'icon' => 'bi-123', 'order_index' => 1],
                    ['title' => 'Penjualan (Visual)', 'url' => 'report/sales/visual', 'icon' => 'bi-pie-chart', 'order_index' => 2],
                    ['title' => 'Laporan Stok', 'url' => 'report/numeric', 'icon' => 'bi-box-seam', 'order_index' => 3],
                    ['title' => 'Laporan Laba Rugi', 'url' => 'pos/analytics', 'icon' => 'bi-cash-stack', 'order_index' => 4, 'roles' => '["SuperAdmin", "TenantOwner"]'],
                ]
            ],
            // Setup
            [
                'title' => 'Setup',
                'url' => null,
                'icon' => 'bi-gear-fill',
                'order_index' => 7,
                'roles' => '["SuperAdmin", "TenantOwner", "Manager"]',
                'children' => [
                    ['title' => 'Pengaturan Toko', 'url' => 'settings/store', 'icon' => 'bi-shop', 'order_index' => 1],
                    ['title' => 'Pajak & Biaya', 'url' => 'settings/taxes', 'icon' => 'bi-receipt-cutoff', 'order_index' => 2],
                ]
            ],
        ];

        foreach ($menus as $menu) {
            $children = $menu['children'] ?? [];
            unset($menu['children']);
            $menu['created_at'] = date('Y-m-d H:i:s');
            $menu['updated_at'] = date('Y-m-d H:i:s');
            
            $this->db->table('menus')->insert($menu);
            $parentId = $this->db->insertID();

            foreach ($children as $child) {
                $child['parent_id'] = $parentId;
                if(!isset($child['roles'])) {
                    $child['roles'] = $menu['roles'];
                }
                $child['created_at'] = date('Y-m-d H:i:s');
                $child['updated_at'] = date('Y-m-d H:i:s');
                $this->db->table('menus')->insert($child);
            }
        }
    }
}
