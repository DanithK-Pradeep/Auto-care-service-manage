<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;

class EmployeeAuth extends BaseController
{
    public function login()
    {
        if (session()->get('employee_logged_in')) {
            return redirect()->to(site_url('employee/dashboard'));
        }

        return view('employee/login', [
            'title' => 'Employee Login'
        ]);
    }

    public function attemptLogin()
    {
        $email    = trim((string)$this->request->getPost('email'));
        $password = (string)$this->request->getPost('password');

        if ($email === '' || $password === '') {
            session()->setFlashdata('error', 'Email and password are required');
            return redirect()->to(site_url('employee/login'))->withInput();
        }

        $employeeModel = new EmployeeModel();
        $emp = $employeeModel->where('email', $email)->first();

        if (!$emp || empty($emp['password']) || !password_verify($password, $emp['password'])) {
            session()->setFlashdata('error', 'Invalid email or password');
            return redirect()->to(site_url('employee/login'))->withInput();
        }

        if (($emp['status'] ?? 'active') !== 'active') {
            session()->setFlashdata('warning', 'Your account is inactive');
            return redirect()->to(site_url('employee/login'))->withInput();
        }

        session()->set([
            'employee_logged_in' => true,
            'employee_id'        => $emp['id'],
            'employee_name'      => trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')),
            'employee_email'     => $emp['email'],
            'employee_role'      => $emp['role'] ?? null,
        ]);

        session()->setFlashdata('success', 'Login successful');
        return redirect()->to(site_url('employee/dashboard'));
    }

    public function logout()
    {
        session()->remove([
            'employee_logged_in',
            'employee_id',
            'employee_name',
            'employee_email',
            'employee_role',
        ]);

        session()->setFlashdata('success', 'Logged out successfully');
        return redirect()->to(site_url('employee/login'));
    }
}
