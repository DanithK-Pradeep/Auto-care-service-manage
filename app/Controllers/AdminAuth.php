<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminAuth extends BaseController
{
    public function showLogin()
    {
        return view('admin/login');
    }

    public function loginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
     // Basic validation
        if (!$username || !$password) {
            return redirect()->back()->with('error', 'Username and password are required!');
        }

        // Load admin model
        $adminModel = new AdminModel();
        $admin = $adminModel->where('username', $username)->first();

        // Check if admin exists
        if (!$admin) {
            return redirect()->back()->with('error', 'Invalid username');
        }

        // Verify hashed password
        if (!password_verify($password, $admin['password'])) {
            return redirect()->back()->with('error', 'Invalid password');
        }

        // Login success → store session
        session()->set('admin_logged_in', true);
        session()->set('admin_id', $admin['id']);
        session()->set('admin_username', $admin['username']);
        
        // Redirect to dashboard
        return redirect()->to('/admin/dashboard');
    }

    public function dashboard()
    {

        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'activeMenu' => 'dashboard'
        ]);
    }


    public function logout()
    {
        // Destroy admin session
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'Logged out successfully');
    }
}   