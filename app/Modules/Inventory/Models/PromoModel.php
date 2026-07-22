<?php

namespace App\Modules\Inventory\Models;

use App\Models\BaseTenantModel;

class PromoModel extends BaseTenantModel
{
    protected $table         = 'promos';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'tenant_id', 'name', 'discount_type', 'discount_value', 'start_date', 'end_date'
    ];
    protected $useTimestamps = true;
}
