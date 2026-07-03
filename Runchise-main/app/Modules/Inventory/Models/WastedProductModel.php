<?php

namespace App\Modules\Inventory\Models;

use App\Models\BaseTenantModel;

class WastedProductModel extends BaseTenantModel
{
    protected $table         = 'wasted_products';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['tenant_id', 'branch_id', 'product_id', 'quantity', 'cost_price', 'reason'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'branch_id'  => 'required|integer',
        'product_id' => 'required|integer',
        'quantity'   => 'required|integer|greater_than[0]',
        'cost_price' => 'required|numeric',
        'reason'     => 'required|min_length[2]|max_length[255]',
    ];
}
