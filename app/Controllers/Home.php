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

        $data = [
            'totalProducts'  => $totalProducts,
            'lowStockCount'  => $lowStockCount,
            'activeSessions' => $activeSessions,
            'lowStockItems'  => $lowStockItems,
            'userName'       => session()->get('user_name') ?? 'Admin NexaPOS',
            'userRole'       => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('dashboard', $data);
    }
}
