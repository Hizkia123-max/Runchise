<?php

namespace App\Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class ProductController extends BaseController
{
    public function index()
    {
        $productModel = new \App\Modules\Inventory\Models\ProductModel();
        $catModel     = new \App\Modules\Inventory\Models\CategoryModel();

        $products = $productModel->findAll();
        $categories = $catModel->findAll();

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
            return redirect()->to('/inventory/products')->with('success', 'Category added successfully.');
        }
        return redirect()->to('/inventory/products')->with('error', implode(', ', $model->errors()));
    }
}
