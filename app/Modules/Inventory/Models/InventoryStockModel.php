<?php

namespace App\Modules\Inventory\Models;

use App\Models\BaseTenantModel;

class InventoryStockModel extends BaseTenantModel
{
    protected $table         = 'inventory_stocks';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['tenant_id','branch_id','product_id','quantity'];
    protected $useTimestamps = true;
}
