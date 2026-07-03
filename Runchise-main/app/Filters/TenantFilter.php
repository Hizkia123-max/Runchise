<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TenantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $host = $request->getUri()->getHost();
        $subdomain = explode('.', $host)[0];

        // For local development access (localhost or IP), default to Tenant ID = 1
        if ($subdomain === 'localhost' || $subdomain === '127' || $subdomain === '0') {
            service('tenant')->setId(1);
            return;
        }

        try {
            // Resolve Tenant record matching subdomain from DB
            $db = \Config\Database::connect();
            if ($db->tableExists('tenants')) {
                $tenant = $db->table('tenants')->where('subdomain', $subdomain)->get()->getRowArray();
                if ($tenant) {
                    service('tenant')->setId($tenant['id']);
                } else {
                    // Fallback to 1 for dev/testing if not found
                    service('tenant')->setId(1);
                }
            } else {
                // Table doesn't exist yet, fallback to 1
                service('tenant')->setId(1);
            }
        } catch (\Throwable $e) {
            // Log warning and fallback
            log_message('warning', 'Tenant resolution failed: ' . $e->getMessage());
            service('tenant')->setId(1);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
