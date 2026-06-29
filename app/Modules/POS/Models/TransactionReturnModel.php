<?php

namespace App\Modules\POS\Models;

use App\Models\BaseTenantModel;

class TransactionReturnModel extends BaseTenantModel
{
    protected $table      = 'transaction_returns';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id', 'branch_id', 'transaction_id', 'total_refunded',
    ];
    protected $useTimestamps = true;
}
