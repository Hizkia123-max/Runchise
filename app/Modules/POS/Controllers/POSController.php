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
}
