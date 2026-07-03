<?php

namespace App\Modules\Authentication\Models;

use App\Models\BaseTenantModel;

class UserModel extends BaseTenantModel
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tenant_id', 'name', 'email', 'password_hash', 'role', 'phone',
        'mfa_secret', 'mfa_enabled',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'email' => 'required|valid_email',
        'name'  => 'required|min_length[2]',
    ];
}
