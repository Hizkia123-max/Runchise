<?php

namespace App\Modules\Report\Controllers;

use App\Controllers\BaseController;

class ReportController extends BaseController
{
    /**
     * Sales Report with date/month/year filter
     */
    public function salesNumeric()
    {
        return $this->salesReportView('App\Modules\Report\Views\sales_report_numeric');
    }

    public function salesVisual()
    {
        return $this->salesReportView('App\Modules\Report\Views\sales_report_visual');
    }

    public function salesReport()
    {
        // Keep original route working just in case
        return $this->salesReportView('App\Modules\Report\Views\sales_report');
    }

    private function salesReportView($viewName)
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        // Filter parameters
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo   = $this->request->getGet('date_to') ?: date('Y-m-d');
        $mode     = $this->request->getGet('mode') ?: 'daily'; // daily, monthly, yearly

        // Build transactions query with date filter
        $transactions = [];
        $summary = ['total_sales' => 0, 'total_transactions' => 0, 'total_items' => 0, 'total_discount' => 0];

        if ($db->tableExists('transactions')) {
            $builder = $db->table('transactions')
                ->where('tenant_id', $tenantId)
                ->where('created_at >=', $dateFrom . ' 00:00:00')
                ->where('created_at <=', $dateTo . ' 23:59:59')
                ->orderBy('created_at', 'DESC');

            $transactions = $builder->get()->getResultArray();

            // Get summary
            $sumBuilder = $db->table('transactions')
                ->select('SUM(total) as total_sales, COUNT(*) as total_transactions, SUM(discount_amount) as total_discount')
                ->where('tenant_id', $tenantId)
                ->where('created_at >=', $dateFrom . ' 00:00:00')
                ->where('created_at <=', $dateTo . ' 23:59:59');

            $sumRow = $sumBuilder->get()->getRowArray();
            $summary['total_sales'] = (float) ($sumRow['total_sales'] ?? 0);
            $summary['total_transactions'] = (int) ($sumRow['total_transactions'] ?? 0);
            $summary['total_discount'] = (float) ($sumRow['total_discount'] ?? 0);

            if ($summary['total_transactions'] > 0) {
                $summary['avg_transaction'] = $summary['total_sales'] / $summary['total_transactions'];
            } else {
                $summary['avg_transaction'] = 0;
            }

            // Get grouped data based on mode
            if ($mode === 'daily') {
                $grouped = $db->table('transactions')
                    ->select("DATE(created_at) as period, SUM(total) as amount, COUNT(*) as count")
                    ->where('tenant_id', $tenantId)
                    ->where('created_at >=', $dateFrom . ' 00:00:00')
                    ->where('created_at <=', $dateTo . ' 23:59:59')
                    ->groupBy("DATE(created_at)")
                    ->orderBy("period", "ASC")
                    ->get()->getResultArray();
            } elseif ($mode === 'monthly') {
                $grouped = $db->table('transactions')
                    ->select("DATE_FORMAT(created_at, '%Y-%m') as period, SUM(total) as amount, COUNT(*) as count")
                    ->where('tenant_id', $tenantId)
                    ->where('created_at >=', $dateFrom . ' 00:00:00')
                    ->where('created_at <=', $dateTo . ' 23:59:59')
                    ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
                    ->orderBy("period", "ASC")
                    ->get()->getResultArray();
            } else {
                $grouped = $db->table('transactions')
                    ->select("YEAR(created_at) as period, SUM(total) as amount, COUNT(*) as count")
                    ->where('tenant_id', $tenantId)
                    ->where('created_at >=', $dateFrom . ' 00:00:00')
                    ->where('created_at <=', $dateTo . ' 23:59:59')
                    ->groupBy("YEAR(created_at)")
                    ->orderBy("period", "ASC")
                    ->get()->getResultArray();
            }
        } else {
            $grouped = [];
        }

        // Payment method breakdown
        $paymentBreakdown = [];
        if ($db->tableExists('transactions')) {
            $paymentBreakdown = $db->table('transactions')
                ->select('payment_method, SUM(total) as amount, COUNT(*) as count')
                ->where('tenant_id', $tenantId)
                ->where('created_at >=', $dateFrom . ' 00:00:00')
                ->where('created_at <=', $dateTo . ' 23:59:59')
                ->groupBy('payment_method')
                ->get()->getResultArray();
        }

        $data = [
            'transactions'     => $transactions,
            'summary'          => $summary,
            'grouped'          => $grouped,
            'paymentBreakdown' => $paymentBreakdown,
            'dateFrom'         => $dateFrom,
            'dateTo'           => $dateTo,
            'mode'             => $mode,
            'userName'         => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'         => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view($viewName, $data);
    }

    /**
     * Stock Card — per product in/out history
     */
    public function stockCard()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $productId = $this->request->getGet('product_id');
        $dateFrom  = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo    = $this->request->getGet('date_to') ?: date('Y-m-d');

        // Get all products for dropdown
        $products = $db->table('products')
            ->where('tenant_id', $tenantId)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $entries = [];
        $selectedProduct = null;

        if ($productId) {
            $selectedProduct = $db->table('products')
                ->where('id', $productId)
                ->get()->getRowArray();

            if ($db->tableExists('stock_card_entries')) {
                $entries = $db->table('stock_card_entries')
                    ->where('tenant_id', $tenantId)
                    ->where('product_id', $productId)
                    ->where('entry_date >=', $dateFrom . ' 00:00:00')
                    ->where('entry_date <=', $dateTo . ' 23:59:59')
                    ->orderBy('entry_date', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->get()->getResultArray();
            }
        }

        $data = [
            'products'        => $products,
            'entries'         => $entries,
            'selectedProduct' => $selectedProduct,
            'productId'       => $productId,
            'dateFrom'        => $dateFrom,
            'dateTo'          => $dateTo,
            'userName'        => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'        => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Report\Views\stock_card', $data);
    }

    /**
     * Stock on Hand — all products current stock
     */
    public function stockOnHand()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $stocks = [];
        $totalValue = 0;
        $totalItems = 0;
        $lowStockCount = 0;

        if ($db->tableExists('inventory_stocks') && $db->tableExists('products')) {
            $stocks = $db->table('products')
                ->select('products.id, products.sku, products.name, products.cost, products.price, products.reorder_point, categories.name as category_name, COALESCE(inventory_stocks.quantity, 0) as quantity, branches.name as branch_name')
                ->join('inventory_stocks', 'inventory_stocks.product_id = products.id', 'left')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->join('branches', 'branches.id = inventory_stocks.branch_id', 'left')
                ->where('products.tenant_id', $tenantId)
                ->orderBy('products.name', 'ASC')
                ->get()->getResultArray();

            foreach ($stocks as $s) {
                $qty = (float) $s['quantity'];
                $totalItems += $qty;
                $totalValue += $qty * (float) $s['cost'];
                if ($qty < (float) $s['reorder_point']) {
                    $lowStockCount++;
                }
            }
        }

        $data = [
            'stocks'        => $stocks,
            'totalValue'    => $totalValue,
            'totalItems'    => $totalItems,
            'lowStockCount' => $lowStockCount,
            'userName'      => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'      => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Report\Views\stock_onhand', $data);
    }

    /**
     * Numeric Report — detailed financial numbers
     */
    public function numericReport()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo   = $this->request->getGet('date_to') ?: date('Y-m-d');

        // Revenue
        $totalRevenue = 0;
        if ($db->tableExists('transactions')) {
            $row = $db->table('transactions')
                ->selectSum('total')
                ->where('tenant_id', $tenantId)
                ->where('created_at >=', $dateFrom . ' 00:00:00')
                ->where('created_at <=', $dateTo . ' 23:59:59')
                ->get()->getRowArray();
            $totalRevenue = (float) ($row['total'] ?? 0);
        }

        // COGS
        $totalCOGS = 0;
        if ($db->tableExists('transaction_items')) {
            $row = $db->table('transaction_items')
                ->select('SUM(transaction_items.quantity * products.cost) as total_cogs')
                ->join('products', 'products.id = transaction_items.product_id')
                ->join('transactions', 'transactions.id = transaction_items.transaction_id')
                ->where('transactions.tenant_id', $tenantId)
                ->where('transactions.created_at >=', $dateFrom . ' 00:00:00')
                ->where('transactions.created_at <=', $dateTo . ' 23:59:59')
                ->get()->getRowArray();
            $totalCOGS = (float) ($row['total_cogs'] ?? 0);
        }

        // Waste
        $totalWaste = 0;
        if ($db->tableExists('wasted_products')) {
            $row = $db->table('wasted_products')
                ->select('SUM(quantity * cost_price) as total_waste')
                ->where('tenant_id', $tenantId)
                ->get()->getRowArray();
            $totalWaste = (float) ($row['total_waste'] ?? 0);
        }

        // Purchase total
        $totalPurchase = 0;
        if ($db->tableExists('purchase_orders')) {
            $row = $db->table('purchase_orders')
                ->selectSum('total_amount')
                ->where('tenant_id', $tenantId)
                ->whereNotIn('status', ['Cancelled', 'Draft'])
                ->where('order_date >=', $dateFrom)
                ->where('order_date <=', $dateTo)
                ->get()->getRowArray();
            $totalPurchase = (float) ($row['total_amount'] ?? 0);
        }

        $grossProfit = $totalRevenue - $totalCOGS;
        $netProfit = $grossProfit - $totalWaste;

        // Per-category sales
        $categorySales = [];
        if ($db->tableExists('transaction_items')) {
            $categorySales = $db->table('transaction_items')
                ->select('categories.name as category_name, SUM(transaction_items.quantity) as qty_sold, SUM(transaction_items.total) as total_sales')
                ->join('products', 'products.id = transaction_items.product_id')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->join('transactions', 'transactions.id = transaction_items.transaction_id')
                ->where('transactions.tenant_id', $tenantId)
                ->where('transactions.created_at >=', $dateFrom . ' 00:00:00')
                ->where('transactions.created_at <=', $dateTo . ' 23:59:59')
                ->groupBy('categories.name')
                ->orderBy('total_sales', 'DESC')
                ->get()->getResultArray();
        }

        // Per-product sales detail
        $productSales = [];
        if ($db->tableExists('transaction_items')) {
            $productSales = $db->table('transaction_items')
                ->select('products.sku, products.name, SUM(transaction_items.quantity) as qty_sold, SUM(transaction_items.total) as total_sales, products.cost, SUM(transaction_items.quantity * products.cost) as total_cogs')
                ->join('products', 'products.id = transaction_items.product_id')
                ->join('transactions', 'transactions.id = transaction_items.transaction_id')
                ->where('transactions.tenant_id', $tenantId)
                ->where('transactions.created_at >=', $dateFrom . ' 00:00:00')
                ->where('transactions.created_at <=', $dateTo . ' 23:59:59')
                ->groupBy('products.id')
                ->orderBy('total_sales', 'DESC')
                ->get()->getResultArray();
        }

        // Payment breakdown
        $paymentBreakdown = [];
        if ($db->tableExists('transactions')) {
            $paymentBreakdown = $db->table('transactions')
                ->select('payment_method, SUM(total) as amount, COUNT(*) as count')
                ->where('tenant_id', $tenantId)
                ->where('created_at >=', $dateFrom . ' 00:00:00')
                ->where('created_at <=', $dateTo . ' 23:59:59')
                ->groupBy('payment_method')
                ->get()->getResultArray();
        }

        $data = [
            'totalRevenue'     => $totalRevenue,
            'totalCOGS'        => $totalCOGS,
            'totalWaste'       => $totalWaste,
            'totalPurchase'    => $totalPurchase,
            'grossProfit'      => $grossProfit,
            'netProfit'        => $netProfit,
            'categorySales'    => $categorySales,
            'productSales'     => $productSales,
            'paymentBreakdown' => $paymentBreakdown,
            'dateFrom'         => $dateFrom,
            'dateTo'           => $dateTo,
            'userName'         => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'         => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Report\Views\numeric_report', $data);
    }
}
