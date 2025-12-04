<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $usermModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $usermModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email not found.');
        }

        // Verify hashed password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid password.');
        }

        // Save user session
        session()->set([
            'isLoggedIn' => true,
            'user_id' => $user['id'],
            'role' => $user['role'],
            'name' => $user['name']
        ]);

        return redirect()->to(site_url('admin/dashboard'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have logged out.');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function createAccount()
    {

        $validation = \Config\Services::validation();

        //define rules
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]|max_length[100]',
            'confirm_password' => 'required|min_length[8]|max_length[100]'
        ];


        if (!$this->validate($rules)) {
            //validation failed
            return redirect()
                ->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $existing = $userModel
            ->where('email', $email)
            ->first();

        if ($existing) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You already have a registered account.');
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'email' => $email,
            'password' => $hashed_password,
            'role' => 'user',
            'name' => 'User'
        ];

        if ($userModel->insert($data)) {
            return redirect()
                ->to('/login')
                ->with('success', 'Account created successfully. Please login.');
        }
    }
}
