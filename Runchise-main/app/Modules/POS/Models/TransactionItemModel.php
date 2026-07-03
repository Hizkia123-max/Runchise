<?php

namespace App\Modules\POS\Models;

use App\Models\BaseTenantModel;

class TransactionItemModel extends BaseTenantModel
{
    protected $table         = 'transaction_items';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'tenant_id','transaction_id','product_id',
        'quantity','unit_price','discount_amount','tax_amount','total',
    ];
    protected $useTimestamps = false;
}
