<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class PurchaseReturnItemModel extends BaseTenantModel
{
    protected $table      = 'purchase_return_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id', 'purchase_return_id', 'purchase_order_item_id', 'product_id', 
        'quantity', 'refund_amount', 'reason', 'deducted_from_stock'
    ];
    protected $useTimestamps = true;
}
