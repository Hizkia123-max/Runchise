<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class PurchaseOrderModel extends BaseTenantModel
{
    protected $table      = 'purchase_orders';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'branch_id', 'supplier_id', 'po_number', 'order_date',
        'expected_date', 'status', 'notes', 'total_amount', 'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
