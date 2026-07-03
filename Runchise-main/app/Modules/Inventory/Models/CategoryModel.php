<?php

namespace App\Modules\Inventory\Models;

use App\Models\BaseTenantModel;

class CategoryModel extends BaseTenantModel
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['tenant_id', 'name'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
    ];
}
