<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class GoodsReceivingModel extends BaseTenantModel
{
    protected $table      = 'goods_receivings';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'branch_id', 'purchase_order_id', 'gr_number',
        'received_date', 'notes', 'received_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
