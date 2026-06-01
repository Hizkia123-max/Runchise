<?php

namespace App\Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class InventoryController extends BaseController
{
    public function stock()
    {
        $stockModel = new \App\Modules\Inventory\Models\InventoryStockModel();
        $data['stocks'] = $stockModel->findAll();
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
