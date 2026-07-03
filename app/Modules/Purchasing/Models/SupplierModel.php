<?php

namespace App\Modules\Purchasing\Models;

use App\Models\BaseTenantModel;

class SupplierModel extends BaseTenantModel
{
    protected $table      = 'suppliers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'name', 'contact_person', 'phone', 'email', 'address',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
