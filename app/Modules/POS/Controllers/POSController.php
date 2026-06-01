<?php

namespace App\Modules\POS\Controllers;

use App\Controllers\BaseController;

class POSController extends BaseController
{
    public function terminal()
    {
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $db = \Config\Database::connect();
        
        $products = $productModel->findAll();
        
        // Let's get actual quantities from stocks table
        $stocks = [];
        if ($db->tableExists('inventory_stocks')) {
            $stocksRaw = $db->table('inventory_stocks')->get()->getResultArray();
            foreach ($stocksRaw as $s) {
                $stocks[$s['product_id']] = $s['quantity'];
            }
        }
        
        foreach ($products as &$p) {
            $p['stock'] = $stocks[$p['id']] ?? 99; // Fallback to 99 for catalog-created products for POS demo
        }
        
        $data['products'] = $products;
        return view('App\Modules\POS\Views\terminal', $data);
    }

    public function sessions()
    {
        $sessionModel = new \App\Modules\POS\Models\POSSessionModel();
        $data['sessions'] = $sessionModel->orderBy('opened_at', 'DESC')->findAll();
        return view('App\Modules\POS\Views\sessions', $data);
    }

    public function analytics()
    {
        $db = \Config\Database::connect();

        // 1. Calculate Total Revenue
        $totalRevenue = 0.0;
        if ($db->tableExists('transactions')) {
            $row = $db->table('transactions')->selectSum('total')->get()->getRowArray();
            $totalRevenue = (float) ($row['total'] ?? 0.0);
        }

        // 2. Calculate Total Wasted Quantity and Waste Financial Loss
        $totalWasteQty = 0;
        $totalWasteCost = 0.0;
        if ($db->tableExists('wasted_products')) {
            $rowQty = $db->table('wasted_products')->selectSum('quantity')->get()->getRowArray();
            $totalWasteQty = (int) ($rowQty['quantity'] ?? 0);

            $rowCost = $db->table('wasted_products')
                ->select('SUM(quantity * cost_price) as total_cost')
                ->get()->getRowArray();
            $totalWasteCost = (float) ($rowCost['total_cost'] ?? 0.0);
        }

        // 3. Payment Methods Inflow Breakdown
        $payments = ['Cash' => 0.0, 'Card' => 0.0, 'QRIS' => 0.0];
        if ($db->tableExists('transactions')) {
            $rows = $db->table('transactions')
                ->select('payment_method, SUM(total) as amount')
                ->groupBy('payment_method')
                ->get()->getResultArray();
            foreach ($rows as $r) {
                if (isset($payments[$r['payment_method']])) {
                    $payments[$r['payment_method']] = (float) $r['amount'];
                }
            }
        }

        // 4. Calculate Sales Cost of Goods Sold (COGS)
        $totalCOGS = 0.0;
        if ($db->tableExists('transaction_items')) {
            $rowCogs = $db->table('transaction_items')
                ->select('SUM(transaction_items.quantity * products.cost) as total_cogs')
                ->join('products', 'products.id = transaction_items.product_id')
                ->get()->getRowArray();
            $totalCOGS = (float) ($rowCogs['total_cogs'] ?? 0.0);
        }

        // 5. Compute Net Profit & Gross Profit
        $grossProfit = $totalRevenue - $totalCOGS;
        $netProfit   = $grossProfit - $totalWasteCost;

        // 6. Gather last 7 days sales for trends
        $dailySales = [];
        if ($db->tableExists('transactions')) {
            $dailyRows = $db->table('transactions')
                ->select("DATE(created_at) as date, SUM(total) as amount")
                ->groupBy("DATE(created_at)")
                ->orderBy("date", "ASC")
                ->limit(7)
                ->get()->getResultArray();
            foreach ($dailyRows as $dr) {
                $dailySales[] = [
                    'date' => date('d M', strtotime($dr['date'])),
                    'amount' => (float) $dr['amount']
                ];
            }
        }
        // Fallback demo data if empty
        if (empty($dailySales)) {
            for ($i = 6; $i >= 0; $i--) {
                $dailySales[] = [
                    'date' => date('d M', strtotime("-$i days")),
                    'amount' => (float) rand(250000, 1500000)
                ];
            }
        }

        // 7. Get 5 Best Selling Items (Favorites)
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
        // Fallback demo data if empty
        if (empty($bestSellers)) {
            $bestSellers = [
                ['name' => 'Wireless Mouse', 'qty' => 18],
                ['name' => 'USB-C Cable 2m', 'qty' => 12],
                ['name' => 'Mechanical Keyboard', 'qty' => 5],
                ['name' => 'LED Monitor 24"', 'qty' => 2],
            ];
        }

        $data = [
            'totalRevenue'   => $totalRevenue,
            'totalWasteQty'  => $totalWasteQty,
            'totalWasteCost' => $totalWasteCost,
            'payments'       => $payments,
            'totalCOGS'      => $totalCOGS,
            'grossProfit'    => $grossProfit,
            'netProfit'      => $netProfit,
            'dailySales'     => $dailySales,
            'bestSellers'    => $bestSellers,
            'userName'       => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'       => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\POS\Views\analytics', $data);
    }
}
