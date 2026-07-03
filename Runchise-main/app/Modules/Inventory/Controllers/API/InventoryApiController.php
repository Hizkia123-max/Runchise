<?php

namespace App\Modules\Inventory\Controllers\API;

use App\Controllers\BaseController;

class InventoryApiController extends BaseController
{
    public function getStocks()
    {
        $stockModel = new \App\Modules\Inventory\Models\InventoryStockModel();
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $branchId = $this->request->getGet('branch_id');
        $limit    = (int) ($this->request->getGet('limit') ?? 50);

        $builder = $stockModel->limit($limit);
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        $stocks = $builder->findAll();

        // Enrich with product info
        foreach ($stocks as &$stock) {
            $product = $productModel->find($stock['product_id']);
            $stock['sku']           = $product['sku']           ?? '';
            $stock['name']          = $product['name']          ?? '';
            $stock['reorder_point'] = $product['reorder_point'] ?? 0;
        }

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Stock levels retrieved',
            'data'    => $stocks,
            'meta'    => ['timestamp' => date('c'), 'count' => count($stocks)],
        ]);
    }

    public function getProducts()
    {
        $model    = new \App\Modules\Inventory\Models\ProductModel();
        $limit    = (int) ($this->request->getGet('limit') ?? 50);
        $search   = $this->request->getGet('q');
        if ($search) {
            $model->groupStart()
                  ->like('name', $search)
                  ->orLike('sku', $search)
                  ->orLike('barcode', $search)
                  ->groupEnd();
        }
        $products = $model->limit($limit)->findAll();
        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'data'    => $products,
            'meta'    => ['timestamp' => date('c'), 'count' => count($products)],
        ]);
    }

    public function getProduct($id)
    {
        $model   = new \App\Modules\Inventory\Models\ProductModel();
        $product = $model->find($id);
        if (!$product) {
            return $this->response->setStatusCode(404)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_RESOURCE_NOT_FOUND',
                'message'    => 'Product not found.',
            ]);
        }
        return $this->response->setStatusCode(200)->setJSON(['success' => true, 'data' => $product]);
    }

    public function createProduct()
    {
        $input = $this->request->getJSON(true);
        $model = new \App\Modules\Inventory\Models\ProductModel();

        $input['tenant_id'] = service('tenant')->getId();

        if (!$model->insert($input)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_VALIDATION_FAILED',
                'message'    => 'Product creation failed.',
                'errors'     => $model->errors(),
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'success'    => true,
            'message'    => 'Product created successfully',
            'product_id' => $model->getInsertID(),
            'meta'       => ['timestamp' => date('c')],
        ]);
    }

    public function createTransfer()
    {
        $input = $this->request->getJSON(true);
        // Transfer logic placeholder
        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'message' => 'Stock transfer initiated',
            'meta'    => ['timestamp' => date('c')],
        ]);
    }
}
