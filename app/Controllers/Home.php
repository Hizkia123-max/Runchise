<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return redirect()->to('/auth/login');
    }

    public function dashboard()
    {
        $db = \Config\Database::connect();
        
        // 1. Stock levels count
        $totalProducts = 0;
        $lowStockCount = 0;
        if ($db->tableExists('products')) {
            $totalProducts = $db->table('products')->countAllResults();
        }
        if ($db->tableExists('inventory_stocks')) {
            // Count items with quantity < reorder_point
            $lowStockCount = $db->table('inventory_stocks')
                ->join('products', 'products.id = inventory_stocks.product_id')
                ->where('inventory_stocks.quantity < products.reorder_point')
                ->countAllResults();
        }

        // 2. Active POS Sessions count
        $activeSessions = 0;
        if ($db->tableExists('pos_sessions')) {
            $activeSessions = $db->table('pos_sessions')->where('status', 'Open')->countAllResults();
        }

        // 3. Low stock products list
        $lowStockItems = [];
        if ($db->tableExists('inventory_stocks')) {
            $lowStockItems = $db->table('inventory_stocks')
                ->select('products.sku, products.name, inventory_stocks.quantity, products.reorder_point')
                ->join('products', 'products.id = inventory_stocks.product_id')
                ->where('inventory_stocks.quantity < products.reorder_point')
                ->limit(5)
                ->get()
                ->getResultArray();
        }

        // 4. Recent sales transactions list
        $recentTransactions = [];
        if ($db->tableExists('transactions')) {
            $recentTransactions = $db->table('transactions')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        }

        // 5. Products list with stock levels ("barang2 nya apa sisa berapa dll")
        $productsStock = [];
        if ($db->tableExists('products')) {
            $builder = $db->table('products')
                ->select('products.sku, products.name, COALESCE(inventory_stocks.quantity, 0) as quantity, products.reorder_point');
            if ($db->tableExists('inventory_stocks')) {
                $builder->join('inventory_stocks', 'inventory_stocks.product_id = products.id', 'left');
            }
            $productsStock = $builder->get()->getResultArray();
        }

        // 6. Best selling products list ("grafik barang terlaris")
        $bestSellers = [];
        if ($db->tableExists('transaction_items')) {
            $bestRows = $db->table('transaction_items')
                ->select('products.name, SUM(transaction_items.quantity) as qty')
                ->join('products', 'products.id = transaction_items.product_id')
                ->groupBy('transaction_items.product_id')
                ->orderBy('qty', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
            foreach ($bestRows as $br) {
                $bestSellers[] = [
                    'name' => $br['name'],
                    'qty'  => (int) $br['qty']
                ];
            }
        }
        if (empty($bestSellers)) {
            $bestSellers = [
                ['name' => 'Wireless Mouse', 'qty' => 18],
                ['name' => 'USB-C Cable 2m', 'qty' => 12],
                ['name' => 'Mechanical Keyboard', 'qty' => 5],
            ];
        }

        $data = [
            'totalProducts'      => $totalProducts,
            'lowStockCount'      => $lowStockCount,
            'activeSessions'     => $activeSessions,
            'lowStockItems'      => $lowStockItems,
            'recentTransactions' => $recentTransactions,
            'productsStock'      => $productsStock,
            'bestSellers'        => $bestSellers,
            'userName'           => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'           => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('dashboard', $data);
    }
}
