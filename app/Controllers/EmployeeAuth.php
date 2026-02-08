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
        $email = trim((string)$this->request->getPost('email'));
        $password = (string)$this->request->getPost('password');

        if ($email === '' || $password === '') {
            return redirect()->back()->with('error', 'Email and password are required');
        }

        $employeeModel = new EmployeeModel();
        $emp = $employeeModel->where('email', $email)->first();

        // don’t reveal which one is wrong (security)
        if (!$emp || empty($emp['password']) || !password_verify($password, $emp['password'])) {
            return redirect()->back()->with('error', 'Invalid email or password');
        }

        if (($emp['status'] ?? 'active') !== 'active') {
            return redirect()->back()->with('error', 'Your account is inactive');
        }

        session()->set([
            'employee_logged_in' => true,
            'employee_id'        => $emp['id'],
            'employee_name'      => ($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? ''),
            'employee_email'     => $emp['email'],
            'employee_role'      => $emp['role'] ?? null,
        ]);

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

        return redirect()->to(site_url('employee/login'))->with('success', 'Logged out');
    }
}
