<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class GoodsReceivingItemModel extends BaseTenantModel
{
    protected $table      = 'goods_receiving_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'goods_receiving_id', 'purchase_order_item_id',
        'product_id', 'quantity_received',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
