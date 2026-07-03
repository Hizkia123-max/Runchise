<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Role-based access control filter.
     * Usage in routes: 'filter' => 'role:TenantOwner,Manager'
     * Allows access only to users whose session role matches one of the allowed roles.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // If no role arguments specified, allow all logged-in users
        if (empty($arguments)) {
            return;
        }

        $userRole = $session->get('user_role');

        if (!in_array($userRole, $arguments)) {
            // User does not have the required role
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
