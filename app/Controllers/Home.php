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
        $tenantId = service('tenant')->getId() ?? 1;
        
        // 1. Stock levels count
        $totalProducts = 0;
        $lowStockCount = 0;
        if ($db->tableExists('products')) {
            $totalProducts = $db->table('products')->where('tenant_id', $tenantId)->countAllResults();
        }
        if ($db->tableExists('inventory_stocks')) {
            $lowStockCount = $db->table('inventory_stocks')
                ->join('products', 'products.id = inventory_stocks.product_id')
                ->where('inventory_stocks.tenant_id', $tenantId)
                ->where('inventory_stocks.quantity < products.reorder_point')
                ->countAllResults();
        }

        // 2. Active POS Sessions count
        $activeSessions = 0;
        if ($db->tableExists('pos_sessions')) {
            $activeSessions = $db->table('pos_sessions')
                ->where('tenant_id', $tenantId)
                ->where('status', 'Open')
                ->countAllResults();
        }

        // 3. Revenue Trend (Last 7 Days)
        $revenueTrend = [];
        if ($db->tableExists('transactions')) {
            $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));
            $trendData = $db->table('transactions')
                ->select("DATE(created_at) as date, SUM(total) as total_revenue")
                ->where('tenant_id', $tenantId)
                ->where('created_at >=', $sevenDaysAgo . ' 00:00:00')
                ->groupBy("DATE(created_at)")
                ->orderBy("date", "ASC")
                ->get()->getResultArray();
            
            // Fill missing days with 0
            $trendMap = [];
            foreach ($trendData as $row) {
                $trendMap[$row['date']] = (float)$row['total_revenue'];
            }
            
            for ($i = 6; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-$i days"));
                $revenueTrend[] = [
                    'date' => date('d M', strtotime($d)),
                    'revenue' => $trendMap[$d] ?? 0
                ];
            }
        }

        // 4. Sales by Category
        $categorySales = [];
        if ($db->tableExists('transaction_items')) {
            $categorySales = $db->table('transaction_items')
                ->select('categories.name as category, SUM(transaction_items.total) as amount')
                ->join('products', 'products.id = transaction_items.product_id')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->join('transactions', 'transactions.id = transaction_items.transaction_id')
                ->where('transactions.tenant_id', $tenantId)
                ->where('transactions.created_at >=', date('Y-m-01') . ' 00:00:00')
                ->groupBy('categories.name')
                ->orderBy('amount', 'DESC')
                ->get()->getResultArray();
        }

        // 5. Best selling products (Month)
        $bestSellers = [];
        if ($db->tableExists('transaction_items')) {
            $bestSellers = $db->table('transaction_items')
                ->select('products.name, SUM(transaction_items.quantity) as qty')
                ->join('products', 'products.id = transaction_items.product_id')
                ->join('transactions', 'transactions.id = transaction_items.transaction_id')
                ->where('transactions.tenant_id', $tenantId)
                ->where('transactions.created_at >=', date('Y-m-01') . ' 00:00:00')
                ->groupBy('transaction_items.product_id')
                ->orderBy('qty', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
        }

        // 6. Purchasing Stats
        $activePO = 0;
        $totalPurchases = 0;
        if ($db->tableExists('purchase_orders')) {
            $activePO = $db->table('purchase_orders')
                ->where('tenant_id', $tenantId)
                ->whereIn('status', ['Ordered', 'Partially Received'])
                ->countAllResults();
                
            $purchaseRow = $db->table('purchase_orders')
                ->selectSum('total_amount')
                ->where('tenant_id', $tenantId)
                ->whereNotIn('status', ['Cancelled', 'Draft'])
                ->where('order_date >=', date('Y-m-01'))
                ->get()->getRowArray();
            $totalPurchases = (float)($purchaseRow['total_amount'] ?? 0);
        }

        $data = [
            'totalProducts'      => $totalProducts,
            'lowStockCount'      => $lowStockCount,
            'activeSessions'     => $activeSessions,
            'revenueTrend'       => $revenueTrend,
            'categorySales'      => $categorySales,
            'bestSellers'        => $bestSellers,
            'activePO'           => $activePO,
            'totalPurchases'     => $totalPurchases,
            'userName'           => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'           => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('dashboard', $data);
    }

    public function activity()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId() ?? 1;
        
        $today = date('Y-m-d');
        
        // Today's transactions
        $transactions = [];
        $totalSales = 0;
        $totalCount = 0;
        if ($db->tableExists('transactions')) {
            $transactions = $db->table('transactions')
                ->select('transactions.*, users.name as cashier_name')
                ->join('pos_sessions', 'pos_sessions.id = transactions.pos_session_id', 'left')
                ->join('users', 'users.id = pos_sessions.user_id', 'left')
                ->where('transactions.tenant_id', $tenantId)
                ->where('DATE(transactions.created_at)', $today)
                ->orderBy('transactions.created_at', 'DESC')
                ->get()->getResultArray();
                
            $totalCount = count($transactions);
            $totalSales = array_sum(array_column($transactions, 'total'));
            
            if ($totalCount > 0 && $db->tableExists('transaction_items')) {
                $txIds = array_column($transactions, 'id');
                $items = $db->table('transaction_items')
                    ->select('transaction_items.*, products.name as product_name')
                    ->join('products', 'products.id = transaction_items.product_id', 'left')
                    ->whereIn('transaction_items.transaction_id', $txIds)
                    ->get()->getResultArray();
                    
                $itemsByTx = [];
                foreach ($items as $item) {
                    $itemsByTx[$item['transaction_id']][] = $item;
                }
                
                foreach ($transactions as &$tx) {
                    $tx['items'] = $itemsByTx[$tx['id']] ?? [];
                }
            }
        }

        $data = [
            'transactions' => $transactions,
            'totalSales' => $totalSales,
            'totalCount' => $totalCount,
            'userName' => session()->get('user_name') ?? 'Admin Runchise',
            'userRole' => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('activity', $data);
    }

    public function reset_database()
    {
        if ($this->request->getGet('token') !== 'runchise2026') {
            return "Unauthorized";
        }

        try {
            $migrate = \Config\Services::migrations();
            $migrate->setNamespace('App');
            $migrate->regress(0);
            $migrate->latest();

            $seeder = \Config\Database::seeder();
            $seeder->call('InitialDataSeeder');

            return "Database successfully reset and seeded on live server!";
        } catch (\Throwable $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
