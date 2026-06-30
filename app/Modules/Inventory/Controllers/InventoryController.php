<?php

namespace App\Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class InventoryController extends BaseController
{
    public function stock()
    {
        $stockModel = new \App\Modules\Inventory\Models\InventoryStockModel();
        $data['stocks'] = $stockModel
            ->select('inventory_stocks.*, products.name as product_name, products.sku as product_sku, branches.name as branch_name, products.reorder_point')
            ->join('products', 'products.id = inventory_stocks.product_id', 'inner')
            ->join('branches', 'branches.id = inventory_stocks.branch_id', 'inner')
            ->findAll();
        return view('App\Modules\Inventory\Views\stock', $data);
    }

    public function opname()
    {
        $db = \Config\Database::connect();
        $stockModel = new \App\Modules\Inventory\Models\InventoryStockModel();
        
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        $data['stocks'] = $stockModel
            ->select('inventory_stocks.*, products.name as product_name, products.sku as product_sku, branches.name as branch_name')
            ->join('products', 'products.id = inventory_stocks.product_id', 'inner')
            ->join('branches', 'branches.id = inventory_stocks.branch_id', 'inner')
            ->findAll();
            
        return view('App\Modules\Inventory\Views\opname', $data);
    }
    
    public function applyOpname()
    {
        $db = \Config\Database::connect();
        $items = $this->request->getPost('items'); // Array of [stock_id => physical_qty]
        $reasons = $this->request->getPost('reasons'); // Array of [stock_id => reason]
        
        if (!empty($items)) {
            foreach ($items as $stockId => $physicalQty) {
                if ($physicalQty === '') continue;
                $physicalQty = (int) $physicalQty;
                
                // Get recorded stock
                $recorded = $db->table('inventory_stocks')->where('id', $stockId)->get()->getRowArray();
                if ($recorded) {
                    // Update stock
                    $db->table('inventory_stocks')->where('id', $stockId)->update([
                        'quantity' => $physicalQty,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    
                    // If physical is less than recorded, optionally log to wasted_products if the reason indicates waste
                    $variance = $physicalQty - $recorded['quantity'];
                    if ($variance < 0) {
                        $lossQty = abs($variance);
                        $reason = $reasons[$stockId] ?? 'Opname Discrepancy';
                        
                        // Get product details
                        $product = $db->table('products')->where('id', $recorded['product_id'])->get()->getRowArray();
                        if ($product) {
                            $db->table('wasted_products')->insert([
                                'tenant_id' => $recorded['tenant_id'],
                                'branch_id' => $recorded['branch_id'],
                                'product_id' => $recorded['product_id'],
                                'quantity' => $lossQty,
                                'cost_price' => $product['cost'],
                                'reason' => $reason,
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }

                    // Log to Stock Card
                    if ($variance != 0) {
                        $db->table('stock_card_entries')->insert([
                            'tenant_id'      => $recorded['tenant_id'],
                            'branch_id'      => $recorded['branch_id'],
                            'product_id'     => $recorded['product_id'],
                            'entry_date'     => date('Y-m-d H:i:s'),
                            'type'           => $variance > 0 ? 'IN' : 'OUT',
                            'quantity'       => abs($variance),
                            'balance_after'  => $physicalQty,
                            'reference_type' => 'Stock Opname',
                            'reference_id'   => $stockId,
                            'reference_code' => 'OPN-' . date('Ymd'),
                            'description'    => 'Opname: ' . ($reasons[$stockId] ?? 'Penyesuaian stok'),
                            'created_at'     => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }
        
        return redirect()->to('/inventory/stock')->with('success', 'Stock Reconciliation applied successfully!');
    }

    public function transfers()
    {
        $db = \Config\Database::connect();
        
        // Ensure at least two branches exist
        $branchesCount = $db->table('branches')->countAllResults();
        if ($branchesCount < 2) {
            $db->table('branches')->insert([
                'tenant_id' => 1,
                'name' => 'Sudirman Branch (Warehouse)',
                'address' => 'Jl. Jenderal Sudirman No. 12, Jakarta',
                'phone' => '021-98765432',
                'latitude' => -6.21,
                'longitude' => 106.81,
                'geo_radius' => 50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        $data['branches'] = $db->table('branches')->get()->getResultArray();
        
        // Load all stocks with product info
        $stockModel = new \App\Modules\Inventory\Models\InventoryStockModel();
        $data['stocks'] = $stockModel
            ->select('inventory_stocks.*, products.name as product_name, products.sku as product_sku, branches.name as branch_name')
            ->join('products', 'products.id = inventory_stocks.product_id', 'inner')
            ->join('branches', 'branches.id = inventory_stocks.branch_id', 'inner')
            ->findAll();
            
        return view('App\Modules\Inventory\Views\transfers', $data);
    }
    
    public function applyTransfer()
    {
        $db = \Config\Database::connect();
        $fromBranch = (int) $this->request->getPost('from_branch');
        $toBranch = (int) $this->request->getPost('to_branch');
        $items = $this->request->getPost('items'); // Array of [product_id => qty]
        
        if ($fromBranch === $toBranch) {
            return redirect()->back()->with('error', 'Source and target branches cannot be the same!');
        }
        
        if (!empty($items)) {
            foreach ($items as $productId => $transferQty) {
                $transferQty = (int) $transferQty;
                if ($transferQty <= 0) continue;
                
                // 1. Deduct from source branch
                $sourceStock = $db->table('inventory_stocks')
                    ->where('branch_id', $fromBranch)
                    ->where('product_id', $productId)
                    ->get()->getRowArray();
                    
                if ($sourceStock) {
                    $newSourceQty = max(0, $sourceStock['quantity'] - $transferQty);
                    $db->table('inventory_stocks')
                        ->where('id', $sourceStock['id'])
                        ->update([
                            'quantity' => $newSourceQty,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    // Log OUT from source branch
                    $db->table('stock_card_entries')->insert([
                        'tenant_id'      => $sourceStock['tenant_id'],
                        'branch_id'      => $fromBranch,
                        'product_id'     => $productId,
                        'entry_date'     => date('Y-m-d H:i:s'),
                        'type'           => 'OUT',
                        'quantity'       => $transferQty,
                        'balance_after'  => $newSourceQty,
                        'reference_type' => 'Stock Transfer',
                        'reference_id'   => null,
                        'reference_code' => 'TRF-' . date('Ymd'),
                        'description'    => 'Transfer keluar ke branch lain',
                        'created_at'     => date('Y-m-d H:i:s'),
                    ]);
                        
                    // 2. Add to target branch
                    $targetStock = $db->table('inventory_stocks')
                        ->where('branch_id', $toBranch)
                        ->where('product_id', $productId)
                        ->get()->getRowArray();

                    $newTargetQty = 0;
                    if ($targetStock) {
                        $newTargetQty = $targetStock['quantity'] + $transferQty;
                        $db->table('inventory_stocks')
                            ->where('id', $targetStock['id'])
                            ->update([
                                'quantity' => $newTargetQty,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                    } else {
                        $newTargetQty = $transferQty;
                        // Create stock record for target branch
                        $db->table('inventory_stocks')->insert([
                            'tenant_id' => $sourceStock['tenant_id'],
                            'branch_id' => $toBranch,
                            'product_id' => $productId,
                            'quantity' => $transferQty,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    // Log IN to target branch
                    $db->table('stock_card_entries')->insert([
                        'tenant_id'      => $sourceStock['tenant_id'],
                        'branch_id'      => $toBranch,
                        'product_id'     => $productId,
                        'entry_date'     => date('Y-m-d H:i:s'),
                        'type'           => 'IN',
                        'quantity'       => $transferQty,
                        'balance_after'  => $newTargetQty,
                        'reference_type' => 'Stock Transfer',
                        'reference_id'   => null,
                        'reference_code' => 'TRF-' . date('Ymd'),
                        'description'    => 'Transfer masuk dari branch lain',
                        'created_at'     => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        
        return redirect()->to('/inventory/stock')->with('success', 'Stock transferred successfully!');
    }
}
