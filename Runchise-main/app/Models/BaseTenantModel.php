<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseTenantModel extends Model
{
    protected $tenantId;

    public function __construct()
    {
        parent::__construct();
        // Resolve active tenant ID from request context
        $this->tenantId = service('tenant')->getId();
    }

    protected function initializeTenantScope()
    {
        // Enforce tenant ID filter on queries
        if ($this->tenantId !== null) {
            $this->builder()->where('tenant_id', $this->tenantId);
        }
    }
}
