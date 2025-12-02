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

    public function loginProcess(){
        $usermModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $usermModel->where('email', $email)->first();

        if(!$user){
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

    public function logout(){
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have logged out.');
    }

}
