<?php

namespace App\Modules\Authentication\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        return view('App\Modules\Authentication\Views\login');
    }

    public function loginPost()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new \App\Modules\Authentication\Models\UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }

        $session = session();
        $session->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
