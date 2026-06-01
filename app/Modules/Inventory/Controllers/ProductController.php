<?php

namespace App\Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class ProductController extends BaseController
{
    public function index()
    {
        $model = new \App\Modules\Inventory\Models\ProductModel();
        $data['products'] = $model->findAll();
        return view('App\Modules\Inventory\Views\products', $data);
    }

    public function create()
    {
        return view('App\Modules\Inventory\Views\products_form');
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
}
