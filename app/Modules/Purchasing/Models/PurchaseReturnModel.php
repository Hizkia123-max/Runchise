<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class PurchaseReturnModel extends BaseTenantModel
{
    protected $table      = 'purchase_returns';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id', 'branch_id', 'purchase_order_id', 'return_number', 'total_refunded', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
}
