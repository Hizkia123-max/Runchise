<?php

namespace App\Modules\Purchasing\Controllers;

use App\Controllers\BaseController;

class PurchasingController extends BaseController
{
    /**
     * List all Purchase Orders
     */
    public function purchaseOrders()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $orders = $db->table('purchase_orders')
            ->select('purchase_orders.*, suppliers.name as supplier_name, users.name as creator_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->join('users', 'users.id = purchase_orders.created_by', 'left')
            ->where('purchase_orders.tenant_id', $tenantId)
            ->orderBy('purchase_orders.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'orders'   => $orders,
            'userName' => session()->get('user_name') ?? 'Admin Runchise',
            'userRole' => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Purchasing\Views\purchase_orders', $data);
    }

    /**
     * Show form to create a new Purchase Order
     */
    public function createPO()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $suppliers = $db->table('suppliers')
            ->where('tenant_id', $tenantId)
            ->get()->getResultArray();

        $products = $db->table('products')
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.tenant_id', $tenantId)
            ->get()->getResultArray();

        $data = [
            'suppliers' => $suppliers,
            'products'  => $products,
            'userName'  => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'  => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Purchasing\Views\purchase_order_form', $data);
    }

    /**
     * Store a new Purchase Order
     */
    public function storePO()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $tenantId = service('tenant')->getId();
            $branchId = 1;

            $supplierId   = (int) $this->request->getPost('supplier_id');
            $orderDate    = $this->request->getPost('order_date') ?: date('Y-m-d');
            $expectedDate = $this->request->getPost('expected_date');
            $notes        = $this->request->getPost('notes');
            $productIds   = $this->request->getPost('product_ids') ?? [];
            $quantities   = $this->request->getPost('quantities') ?? [];
            $unitCosts    = $this->request->getPost('unit_costs') ?? [];

            if (empty($productIds) || empty($supplierId)) {
                return redirect()->back()->with('error', 'Supplier dan minimal 1 produk harus dipilih.');
            }

            // Generate PO number
            $lastPO = $db->table('purchase_orders')
                ->where('tenant_id', $tenantId)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()->getRowArray();
            $nextNum = $lastPO ? ((int) substr($lastPO['po_number'], 3)) + 1 : 1;
            $poNumber = 'PO-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

            $totalAmount = 0;
            $items = [];

            foreach ($productIds as $idx => $productId) {
                $qty = (float) ($quantities[$idx] ?? 0);
                $cost = (float) ($unitCosts[$idx] ?? 0);
                if ($qty <= 0 || $cost <= 0) continue;

                $lineTotal = $qty * $cost;
                $totalAmount += $lineTotal;

                $items[] = [
                    'tenant_id'         => $tenantId,
                    'product_id'        => $productId,
                    'quantity_ordered'  => $qty,
                    'quantity_received' => 0,
                    'unit_cost'         => $cost,
                    'total_cost'        => $lineTotal,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];
            }

            if (empty($items)) {
                return redirect()->back()->with('error', 'Tidak ada item valid dalam PO.');
            }

            // Insert PO header
            $db->table('purchase_orders')->insert([
                'tenant_id'     => $tenantId,
                'branch_id'     => $branchId,
                'supplier_id'   => $supplierId,
                'po_number'     => $poNumber,
                'order_date'    => $orderDate,
                'expected_date' => $expectedDate ?: null,
                'status'        => 'Ordered',
                'notes'         => $notes,
                'total_amount'  => $totalAmount,
                'created_by'    => session()->get('user_id'),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            $poId = $db->insertID();

            // Insert PO items
            foreach ($items as &$item) {
                $item['purchase_order_id'] = $poId;
                $db->table('purchase_order_items')->insert($item);
            }

            $db->transComplete();

            if (!$db->transStatus()) {
                throw new \RuntimeException('DB transaction failed.');
            }

            return redirect()->to('/purchasing/orders')->with('success', "Purchase Order {$poNumber} berhasil dibuat!");
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal membuat PO: ' . $e->getMessage());
        }
    }

    /**
     * Show form to receive goods from a Purchase Order
     */
    public function receivePO($poId)
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $po = $db->table('purchase_orders')
            ->select('purchase_orders.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->where('purchase_orders.id', $poId)
            ->where('purchase_orders.tenant_id', $tenantId)
            ->get()->getRowArray();

        if (!$po) {
            return redirect()->to('/purchasing/orders')->with('error', 'PO tidak ditemukan.');
        }

        $items = $db->table('purchase_order_items')
            ->select('purchase_order_items.*, products.name as product_name, products.sku')
            ->join('products', 'products.id = purchase_order_items.product_id', 'left')
            ->where('purchase_order_items.purchase_order_id', $poId)
            ->get()->getResultArray();

        $data = [
            'po'       => $po,
            'items'    => $items,
            'userName' => session()->get('user_name') ?? 'Admin Runchise',
            'userRole' => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Purchasing\Views\receive_form', $data);
    }

    /**
     * Process goods receiving — update stock + stock card
     */
    public function storeReceiving()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $tenantId = service('tenant')->getId();
            $branchId = 1;

            $poId = (int) $this->request->getPost('purchase_order_id');
            $receivedQtys = $this->request->getPost('received_quantities') ?? [];
            $notes = $this->request->getPost('notes');

            $po = $db->table('purchase_orders')
                ->where('id', $poId)
                ->where('tenant_id', $tenantId)
                ->get()->getRowArray();

            if (!$po) {
                throw new \RuntimeException('PO tidak ditemukan.');
            }

            if (in_array($po['status'], ['Completed', 'Cancelled'])) {
                throw new \RuntimeException('PO sudah selesai atau dibatalkan.');
            }

            // Generate GR number
            $lastGR = $db->table('goods_receivings')
                ->where('tenant_id', $tenantId)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()->getRowArray();
            $nextGRNum = $lastGR ? ((int) substr($lastGR['gr_number'], 3)) + 1 : 1;
            $grNumber = 'GR-' . str_pad($nextGRNum, 5, '0', STR_PAD_LEFT);

            // Insert GR header
            $db->table('goods_receivings')->insert([
                'tenant_id'         => $tenantId,
                'branch_id'         => $branchId,
                'purchase_order_id' => $poId,
                'gr_number'         => $grNumber,
                'received_date'     => date('Y-m-d'),
                'notes'             => $notes,
                'received_by'       => session()->get('user_id'),
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
            $grId = $db->insertID();

            $hasItems = false;
            $allFullyReceived = true;

            // Process each PO item
            $poItems = $db->table('purchase_order_items')
                ->where('purchase_order_id', $poId)
                ->get()->getResultArray();

            foreach ($poItems as $poItem) {
                $qtyReceiving = (float) ($receivedQtys[$poItem['id']] ?? 0);
                if ($qtyReceiving <= 0) {
                    // Check if this item is still outstanding
                    if ($poItem['quantity_received'] < $poItem['quantity_ordered']) {
                        $allFullyReceived = false;
                    }
                    continue;
                }

                $hasItems = true;
                $maxReceivable = $poItem['quantity_ordered'] - $poItem['quantity_received'];
                $qtyReceiving = min($qtyReceiving, $maxReceivable);

                // Insert GR item
                $db->table('goods_receiving_items')->insert([
                    'tenant_id'              => $tenantId,
                    'goods_receiving_id'     => $grId,
                    'purchase_order_item_id' => $poItem['id'],
                    'product_id'             => $poItem['product_id'],
                    'quantity_received'      => $qtyReceiving,
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ]);

                // Update PO item received qty
                $newReceivedQty = $poItem['quantity_received'] + $qtyReceiving;
                $db->table('purchase_order_items')
                    ->where('id', $poItem['id'])
                    ->update([
                        'quantity_received' => $newReceivedQty,
                        'updated_at'        => date('Y-m-d H:i:s'),
                    ]);

                if ($newReceivedQty < $poItem['quantity_ordered']) {
                    $allFullyReceived = false;
                }

                // Update inventory stock
                $stock = $db->table('inventory_stocks')
                    ->where('tenant_id', $tenantId)
                    ->where('branch_id', $branchId)
                    ->where('product_id', $poItem['product_id'])
                    ->get()->getRowArray();

                if ($stock) {
                    $newStockQty = $stock['quantity'] + $qtyReceiving;
                    $db->table('inventory_stocks')
                        ->where('id', $stock['id'])
                        ->update([
                            'quantity'   => $newStockQty,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                } else {
                    $newStockQty = $qtyReceiving;
                    $db->table('inventory_stocks')->insert([
                        'tenant_id'  => $tenantId,
                        'branch_id'  => $branchId,
                        'product_id' => $poItem['product_id'],
                        'quantity'   => $qtyReceiving,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Log to Stock Card
                $db->table('stock_card_entries')->insert([
                    'tenant_id'      => $tenantId,
                    'branch_id'      => $branchId,
                    'product_id'     => $poItem['product_id'],
                    'entry_date'     => date('Y-m-d H:i:s'),
                    'type'           => 'IN',
                    'quantity'       => $qtyReceiving,
                    'balance_after'  => $newStockQty,
                    'reference_type' => 'Goods Receiving',
                    'reference_id'   => $grId,
                    'reference_code' => $grNumber,
                    'description'    => "Penerimaan dari PO {$po['po_number']}",
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }

            if (!$hasItems) {
                throw new \RuntimeException('Tidak ada item yang diterima.');
            }

            // Update PO status
            $newStatus = $allFullyReceived ? 'Completed' : 'Partially Received';
            $db->table('purchase_orders')
                ->where('id', $poId)
                ->update([
                    'status'     => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $db->transComplete();

            if (!$db->transStatus()) {
                throw new \RuntimeException('DB transaction failed.');
            }

            return redirect()->to('/purchasing/orders')->with('success', "Penerimaan {$grNumber} berhasil diproses!");
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Show goods receiving history
     */
    public function receivingHistory()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $receivings = $db->table('goods_receivings')
            ->select('goods_receivings.*, purchase_orders.po_number, suppliers.name as supplier_name, users.name as receiver_name')
            ->join('purchase_orders', 'purchase_orders.id = goods_receivings.purchase_order_id', 'left')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->join('users', 'users.id = goods_receivings.received_by', 'left')
            ->where('goods_receivings.tenant_id', $tenantId)
            ->orderBy('goods_receivings.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get items for each receiving
        foreach ($receivings as &$gr) {
            $gr['items'] = $db->table('goods_receiving_items')
                ->select('goods_receiving_items.*, products.name as product_name, products.sku')
                ->join('products', 'products.id = goods_receiving_items.product_id', 'left')
                ->where('goods_receiving_items.goods_receiving_id', $gr['id'])
                ->get()->getResultArray();
        }

        $data = [
            'receivings' => $receivings,
            'userName'   => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'   => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Purchasing\Views\receivings', $data);
    }

    /**
     * Manage suppliers
     */
    public function suppliers()
    {
        $db = \Config\Database::connect();
        $tenantId = service('tenant')->getId();

        $suppliers = $db->table('suppliers')
            ->where('tenant_id', $tenantId)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $data = [
            'suppliers' => $suppliers,
            'userName'  => session()->get('user_name') ?? 'Admin Runchise',
            'userRole'  => session()->get('user_role') ?? 'TenantOwner',
        ];

        return view('App\Modules\Purchasing\Views\suppliers', $data);
    }

    /**
     * Store a new supplier
     */
    public function storeSupplier()
    {
        $model = new \App\Modules\Purchasing\Models\SupplierModel();
        $input = $this->request->getPost();
        $input['tenant_id'] = service('tenant')->getId();

        if ($model->insert($input)) {
            return redirect()->to('/purchasing/suppliers')->with('success', 'Supplier berhasil ditambahkan.');
        }
        return redirect()->back()->with('error', 'Gagal menambahkan supplier.');
    }
}
