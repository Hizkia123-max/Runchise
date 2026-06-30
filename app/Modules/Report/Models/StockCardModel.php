<?php

namespace App\Modules\Report\Models;

use App\Models\BaseTenantModel;

class StockCardModel extends BaseTenantModel
{
    protected $table      = 'stock_card_entries';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'branch_id', 'product_id', 'entry_date', 'type',
        'quantity', 'balance_after', 'reference_type', 'reference_id',
        'reference_code', 'description',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
