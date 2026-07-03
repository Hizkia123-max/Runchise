<?php

namespace App\Modules\POS\Models;

use App\Models\BaseTenantModel;

class TransactionModel extends BaseTenantModel
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id','branch_id','pos_session_id','customer_id',
        'invoice_number','subtotal','discount_amount','tax_amount',
        'total','payment_method','payment_status',
    ];
    protected $useTimestamps = true;
}
