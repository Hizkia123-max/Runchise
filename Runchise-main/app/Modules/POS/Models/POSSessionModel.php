<?php

namespace App\Modules\POS\Models;

use App\Models\BaseTenantModel;

class POSSessionModel extends BaseTenantModel
{
    protected $table         = 'pos_sessions';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'tenant_id','branch_id','user_id',
        'opening_cash','closing_cash','status','opened_at','closed_at',
    ];
    protected $useTimestamps = false;
}
