<?php

namespace App\Modules\Authentication\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class AuthApiController extends BaseController
{
    public function login()
    {
        $email    = $this->request->getJSON(true)['email']    ?? '';
        $password = $this->request->getJSON(true)['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_VALIDATION_FAILED',
                'message'    => 'Email and password are required.',
            ]);
        }

        $userModel = new \App\Modules\Authentication\Models\UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_INVALID_TOKEN',
                'message'    => 'Invalid credentials.',
            ]);
        }

        $payload = [
            'iss'     => base_url(),
            'aud'     => base_url(),
            'iat'     => time(),
            'exp'     => time() + 3600,
            'user_id' => $user['id'],
            'role'    => $user['role'],
        ];

        $jwtSecret = getenv('JWT_SECRET') ?: 'nexapos_default_jwt_secret_key_2026';
        $token = base64_encode(json_encode($payload)); // Simplified without firebase/jwt for base setup

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Authentication successful',
            'data'    => [
                'token'      => $token,
                'expires_in' => 3600,
                'user'       => [
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role'],
                ],
            ],
            'meta' => ['timestamp' => date('c')],
        ]);
    }
}
