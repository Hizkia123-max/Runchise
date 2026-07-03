<?php

namespace App\Services;

class TenantService
{
    protected $tenantId = null;

    public function setId($id)
    {
        $this->tenantId = $id;
    }

    public function getId()
    {
        return $this->tenantId;
    }
}
