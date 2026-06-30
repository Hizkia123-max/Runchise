<?php

namespace App\Modules\POS\Controllers;

use App\Controllers\BaseController;

class POSController extends BaseController
{
    public function terminal()
    {
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $db = \Config\Database::connect();
        
        // Dynamic self-healing database seeding for rich products
        $productsCount = $productModel->countAllResults();
        if ($productsCount <= 5) {
            $tenantId = 1; // Default tenant
            
            // Fetch or insert categories
            $categories = ['Food & Beverage', 'Retail', 'Electronics', 'Fashion', 'Services'];
            $categoryIds = [];
            foreach ($categories as $catName) {
                $existingCat = $db->table('categories')->where('name', $catName)->get()->getRowArray();
                if ($existingCat) {
                    $categoryIds[$catName] = $existingCat['id'];
                } else {
                    $db->table('categories')->insert([
                        'tenant_id'  => $tenantId,
                        'name'       => $catName,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $categoryIds[$catName] = $db->insertID();
                }
            }
            
            // Build rich list of products
            $richProducts = [
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
            
            // Insert each product if it doesn't already exist by SKU
            foreach ($richProducts as $rp) {
                $existing = $db->table('products')->where('sku', $rp['sku'])->get()->getRowArray();
                if (!$existing) {
                    $db->table('products')->insert(array_merge($rp, [
                        'tenant_id'  => $tenantId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]));
                    $newProdId = $db->insertID();
                    
                    // Insert stock levels so it isn't out of stock
                    $db->table('inventory_stocks')->insert([
                        'tenant_id'  => $tenantId,
                        'branch_id'  => 1,
                        'product_id' => $newProdId,
                        'quantity'   => rand(20, 80),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        
        $products = $productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->findAll();
        
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

    public function returns()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();
        
        // Fetch all transactions for this tenant
        $transactions = [];
        if ($db->tableExists('transactions')) {
            $transactions = $db->table('transactions')
                ->select('transactions.*, pos_sessions.status as session_status')
                ->join('pos_sessions', 'pos_sessions.id = transactions.pos_session_id', 'left')
                ->where('transactions.tenant_id', $tenantId)
                ->orderBy('transactions.created_at', 'DESC')
                ->get()
                ->getResultArray();
                
            // For each transaction, get its items and the already returned quantities
            foreach ($transactions as &$tx) {
                $tx['items'] = $db->table('transaction_items')
                    ->select('transaction_items.*, products.name as product_name, products.sku')
                    ->join('products', 'products.id = transaction_items.product_id', 'left')
                    ->where('transaction_items.transaction_id', $tx['id'])
                    ->get()
                    ->getResultArray();
                
                // Get already returned quantities for each item in this transaction
                foreach ($tx['items'] as &$item) {
                    $returned = $db->table('transaction_return_items')
                        ->selectSum('quantity')
                        ->where('transaction_item_id', $item['id'])
                        ->get()
                        ->getRowArray();
                    $item['returned_quantity'] = (float)($returned['quantity'] ?? 0.0);
                }
            }
        }

        // Fetch return history
        $returnsHistory = [];
        if ($db->tableExists('transaction_returns')) {
            $returnsHistory = $db->table('transaction_returns')
                ->select('transaction_returns.*, transactions.invoice_number, transactions.created_at as transaction_date')
                ->join('transactions', 'transactions.id = transaction_returns.transaction_id', 'left')
                ->where('transaction_returns.tenant_id', $tenantId)
                ->orderBy('transaction_returns.created_at', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($returnsHistory as &$ret) {
                $ret['items'] = $db->table('transaction_return_items')
                    ->select('transaction_return_items.*, products.name as product_name, products.sku')
                    ->join('products', 'products.id = transaction_return_items.product_id', 'left')
                    ->where('transaction_return_items.return_id', $ret['id'])
                    ->get()
                    ->getResultArray();
            }
        }
        
        $data = [
            'transactions' => $transactions,
            'returnsHistory' => $returnsHistory,
            'userName'       => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'       => session()->get('user_role') ?? 'TenantOwner',
        ];
        
        return view('App\Modules\POS\Views\returns', $data);
    }

    public function processReturn()
    {
        $txId = $this->request->getPost('transaction_id');
        $returnItems = $this->request->getPost('return_items'); // array of itemId => qty to return
        $returnReasons = $this->request->getPost('return_reasons'); // array of itemId => reason string
        $returnToStock = $this->request->getPost('return_to_stock'); // array of itemId => 1 or 0
        
        if (empty($txId) || empty($returnItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk diretur.');
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $tenantId = service('tenant')->getId();
            
            // Get original transaction
            $tx = $db->table('transactions')
                ->where('id', $txId)
                ->where('tenant_id', $tenantId)
                ->get()
                ->getRowArray();
                
            if (!$tx) {
                throw new \RuntimeException('Transaksi tidak ditemukan.');
            }
            
            $totalRefund = 0.0;
            $itemsToReturnData = [];
            
            foreach ($returnItems as $itemId => $qtyToReturn) {
                $qtyToReturn = (float) $qtyToReturn;
                if ($qtyToReturn <= 0) continue;
                
                // Get transaction item
                $txItem = $db->table('transaction_items')->where('id', $itemId)->get()->getRowArray();
                if (!$txItem) {
                    throw new \RuntimeException('Item transaksi tidak ditemukan.');
                }
                
                // Check already returned quantity
                $alreadyReturned = $db->table('transaction_return_items')
                    ->selectSum('quantity')
                    ->where('transaction_item_id', $itemId)
                    ->get()
                    ->getRowArray();
                $returnedQty = (float)($alreadyReturned['quantity'] ?? 0.0);
                
                if (($qtyToReturn + $returnedQty) > $txItem['quantity']) {
                    throw new \RuntimeException('Total jumlah retur melebihi jumlah pembelian untuk item: ' . $itemId);
                }
                
                // Calculate refund amount proportionately
                $refundForLine = ($txItem['total'] / $txItem['quantity']) * $qtyToReturn;
                $totalRefund += $refundForLine;
                
                $itemsToReturnData[] = [
                    'transaction_item_id' => $itemId,
                    'product_id'          => $txItem['product_id'],
                    'quantity'            => $qtyToReturn,
                    'refund_amount'       => $refundForLine,
                    'reason'              => $returnReasons[$itemId] ?? 'Retur',
                    'returned_to_stock'   => isset($returnToStock[$itemId]) ? 1 : 0
                ];
            }

            if (empty($itemsToReturnData)) {
                throw new \RuntimeException('Kuantitas retur tidak valid.');
            }
            
            // Insert Return Header
            $db->table('transaction_returns')->insert([
                'tenant_id'      => $tenantId,
                'branch_id'      => $tx['branch_id'],
                'transaction_id' => $txId,
                'total_refunded' => $totalRefund,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
            
            $returnId = $db->insertID();
            
            // Insert Return Items and adjust inventory stock
            foreach ($itemsToReturnData as $itemData) {
                $db->table('transaction_return_items')->insert([
                    'tenant_id'           => $tenantId,
                    'return_id'           => $returnId,
                    'transaction_item_id' => $itemData['transaction_item_id'],
                    'product_id'          => $itemData['product_id'],
                    'quantity'            => $itemData['quantity'],
                    'refund_amount'       => $itemData['refund_amount'],
                    'reason'              => $itemData['reason'],
                    'returned_to_stock'   => $itemData['returned_to_stock'],
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s')
                ]);
                
                // Adjust stock if requested
                if ($itemData['returned_to_stock'] === 1) {
                    $stock = $db->table('inventory_stocks')
                        ->where('tenant_id', $tenantId)
                        ->where('branch_id', $tx['branch_id'])
                        ->where('product_id', $itemData['product_id'])
                        ->get()
                        ->getRowArray();
                        
                    if ($stock) {
                        $db->table('inventory_stocks')
                            ->where('id', $stock['id'])
                            ->update([
                                'quantity' => $stock['quantity'] + $itemData['quantity'],
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                    } else {
                        // Create a stock entry if it doesn't exist
                        $db->table('inventory_stocks')->insert([
                            'tenant_id'  => $tenantId,
                            'branch_id'  => $tx['branch_id'],
                            'product_id' => $itemData['product_id'],
                            'quantity'   => $itemData['quantity'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            
            // Calculate total items returned for this transaction
            $totalTxItems = $db->table('transaction_items')
                ->selectSum('quantity')
                ->where('transaction_id', $txId)
                ->get()
                ->getRowArray();
            $totalTxQty = (float)($totalTxItems['quantity'] ?? 0.0);
            
            $totalRetItems = $db->table('transaction_return_items')
                ->selectSum('quantity')
                ->join('transaction_returns', 'transaction_returns.id = transaction_return_items.return_id')
                ->where('transaction_returns.transaction_id', $txId)
                ->get()
                ->getRowArray();
            $totalRetQty = (float)($totalRetItems['quantity'] ?? 0.0);
            
            // Update transaction payment status
            $newStatus = ($totalRetQty >= $totalTxQty) ? 'Refunded' : 'Paid';
            
            $db->table('transactions')
                ->where('id', $txId)
                ->update([
                    'payment_status' => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
            $db->transComplete();
            
            if (!$db->transStatus()) {
                throw new \RuntimeException('DB transaction failed.');
            }
            
            return redirect()->to('/pos/returns')->with('success', 'Retur transaksi berhasil diproses! Total Refund: Rp ' . number_format($totalRefund, 0, ',', '.'));
            
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }
}
