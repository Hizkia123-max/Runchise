<?php

namespace App\Modules\Inventory\Models;

use App\Models\BaseTenantModel;

class ProductModel extends BaseTenantModel
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'tenant_id','sku','name','barcode','price','cost','reorder_point',
        'category_id','brand_id','description',
    ];
    protected $useTimestamps = true;
    protected $validationRules = [
        'sku'   => 'required|max_length[50]',
        'name'  => 'required|min_length[2]',
        'price' => 'required|numeric',
    ];
}
