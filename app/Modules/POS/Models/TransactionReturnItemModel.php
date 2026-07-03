<?php

namespace App\Modules\POS\Models;

use App\Models\BaseTenantModel;

class TransactionReturnItemModel extends BaseTenantModel
{
    protected $table      = 'transaction_return_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id', 'return_id', 'transaction_item_id', 'product_id',
        'quantity', 'refund_amount', 'reason', 'returned_to_stock',
    ];
    protected $useTimestamps = true;
}
