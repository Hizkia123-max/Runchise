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

    public function transfers()
    {
        return view('App\Modules\Inventory\Views\transfers');
    }

    public function opname()
    {
        return view('App\Modules\Inventory\Views\opname');
    }
}
