<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class PurchaseOrderItemModel extends BaseTenantModel
{
    protected $table      = 'purchase_order_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'purchase_order_id', 'product_id',
        'quantity_ordered', 'quantity_received', 'unit_cost', 'total_cost',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
