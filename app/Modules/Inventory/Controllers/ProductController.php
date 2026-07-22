<?php

namespace App\Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class ProductController extends BaseController
{
    public function index()
    {
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $catModel     = new \App\Modules\Inventory\Models\CategoryModel();
        $wastedModel  = new \App\Modules\Inventory\Models\WastedProductModel();

        $products = $productModel->findAll();
        $categories = $catModel->findAll();
        
        $wastedLogs = $wastedModel
            ->select('wasted_products.*, products.name as product_name, products.sku as product_sku')
            ->join('products', 'products.id = wasted_products.product_id')
            ->orderBy('wasted_products.created_at', 'DESC')
            ->findAll();

        // Create a lookup map for category names
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat['id']] = $cat['name'];
        }

        foreach ($products as &$p) {
            $p['category_name'] = $catMap[$p['category_id'] ?? ''] ?? 'Uncategorized';
        }

        $data['products']   = $products;
        $data['categories'] = $categories;
        $data['wastedLogs'] = $wastedLogs;

        return view('App\Modules\Inventory\Views\products', $data);
    }

    public function create()
    {
        $catModel = new \App\Modules\Inventory\Models\CategoryModel();
        $data['categories'] = $catModel->findAll();
        return view('App\Modules\Inventory\Views\products_form', $data);
    }

    public function store()
    {
        $model = new \App\Modules\Inventory\Models\ProductModel();
        $input = $this->request->getPost();
        $input['tenant_id'] = service('tenant')->getId();
        if ($model->insert($input)) {
            return redirect()->to('/inventory/products')->with('success', 'Product created.');
        }
        return redirect()->back()->with('error', implode(', ', $model->errors()));
    }

    public function edit($id)
    {
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $catModel     = new \App\Modules\Inventory\Models\CategoryModel();

        $data['product'] = $productModel->find($id);
        if (!$data['product']) {
            return redirect()->to('/inventory/products')->with('error', 'Product not found.');
        }

        $data['categories'] = $catModel->findAll();
        return view('App\Modules\Inventory\Views\products_form', $data);
    }

    public function update($id)
    {
        $model = new \App\Modules\Inventory\Models\ProductModel();
        $input = $this->request->getPost();
        if ($model->update($id, $input)) {
            return redirect()->to('/inventory/products')->with('success', 'Product updated.');
        }
        return redirect()->back()->with('error', implode(', ', $model->errors()));
    }

    public function delete($id)
    {
        $model = new \App\Modules\Inventory\Models\ProductModel();
        if ($model->delete($id)) {
            return redirect()->to('/inventory/products')->with('success', 'Product deleted.');
        }
        return redirect()->to('/inventory/products')->with('error', 'Failed to delete product.');
    }

    public function categoryStore()
    {
        $model = new \App\Modules\Inventory\Models\CategoryModel();
        $input = $this->request->getPost();
        $input['tenant_id'] = service('tenant')->getId();

        if ($model->insert($input)) {
            return redirect()->back()->with('success', 'Category added successfully.');
        }
        return redirect()->back()->with('error', implode(', ', $model->errors()));
    }

    public function wastedStore()
    {
        $wastedModel = new \App\Modules\Inventory\Models\WastedProductModel();
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $stockModel   = new \App\Modules\Inventory\Models\InventoryStockModel();

        $input = $this->request->getPost();
        $tenantId = service('tenant')->getId();
        $branchId = 1; // Default Main Branch

        $productId = (int) ($input['product_id'] ?? 0);
        $qty       = (int) ($input['quantity'] ?? 0);
        $reason    = $input['reason'] ?? '';

        $product = $productModel->find($productId);
        if (!$product) {
            return redirect()->to('/inventory/products#wasted-pane')->with('error', 'Product not found.');
        }

        if ($qty <= 0) {
            return redirect()->to('/inventory/products#wasted-pane')->with('error', 'Quantity must be greater than 0.');
        }

        // Deduct inventory stock
        $stock = $stockModel
            ->where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        if (!$stock || $stock['quantity'] < $qty) {
            $currentStock = $stock ? $stock['quantity'] : 0;
            return redirect()->to('/inventory/products#wasted-pane')->with('error', "Insufficient stock. Current available: {$currentStock}.");
        }

        // DB Transaction for atomicity
        $db = \Config\Database::connect();
        $db->transStart();

        $stockModel->update($stock['id'], [
            'quantity' => $stock['quantity'] - $qty
        ]);

        $wastedModel->insert([
            'tenant_id'  => $tenantId,
            'branch_id'  => $branchId,
            'product_id' => $productId,
            'quantity'   => $qty,
            'cost_price' => $product['cost'],
            'reason'     => $reason
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/inventory/products#wasted-pane')->with('error', 'Failed to save wasted product log.');
        }

        return redirect()->to('/inventory/products#wasted-pane')->with('success', 'Wasted product logged and stock deducted.');
    }

    public function promos()
    {
        $promoModel = new \App\Modules\Inventory\Models\PromoModel();
        $data['promos'] = $promoModel->findAll();
        return view('App\Modules\Inventory\Views\promos', $data);
    }

    public function promoStore()
    {
        $promoModel = new \App\Modules\Inventory\Models\PromoModel();
        $input = $this->request->getPost();
        $input['tenant_id'] = service('tenant')->getId();

        if ($promoModel->insert($input)) {
            return redirect()->to('/inventory/promos')->with('success', 'Promo berhasil ditambahkan.');
        }
        return redirect()->to('/inventory/promos')->with('error', 'Gagal menambahkan promo.');
    }

    public function categories()
    {
        $catModel = new \App\Modules\Inventory\Models\CategoryModel();
        $data['categories'] = $catModel->findAll();
        return view('App\Modules\Inventory\Views\categories', $data);
    }
}
