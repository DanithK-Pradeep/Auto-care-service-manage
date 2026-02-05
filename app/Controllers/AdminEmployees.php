<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\StationModel;
use App\Models\StationTypeModel;
use App\Models\AssignModel;
use Config\Services;

class AdminEmployees extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')->with('error', 'Please login first');
        }

        $data = [
            'title' => 'Employees Dashboard',
            'activeMenu' => 'employees'
        ];

        return view('admin/employees/dashboard', $data);
    }

    public function index()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $employeeModel = new EmployeeModel();

        $data = [
            'title' => 'All Employees',
            'activeMenu' => 'employees',
            'employees' => $employeeModel->findAll()
        ];

        return view('admin/employees/viewlist', $data);
    }



    public function create()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/employees/create', [
            'title' => 'Add Employee',
            'activeMenu' => 'employees'
        ]);
    }

    public function store()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        // Check if email already exists
        $email = $this->request->getPost('email');
        $employeeModel = new EmployeeModel();
        $existingEmployee = $employeeModel->where('email', $email)->first();
        if ($existingEmployee) {
            return redirect()->back()->withInput()->with('error', 'Email already exists');
        }

        // Get form input
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('password_confirmation');

        // Validate password confirmation
        if ($password !== $confirmPassword) {

            return redirect()->back()->withInput()->with('error', 'Passwords do not match');
        }

        // Hash password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert employee
        $employeeModel = new EmployeeModel();
        $employeeModel->insert([

            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'phone'      => $this->request->getPost('phone'),
            'email'      => $this->request->getPost('email'),
            'password'   => $hashedPassword,
            'role'       => $this->request->getPost('role'),
            'status'     => $this->request->getPost('status'),
            // Store hashed password
        ]);

        return redirect()->to('admin/employees/create')
            ->with('success', 'Employee added successfully');
    }

    public function view($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);

        if (!$employee) {
            return redirect()->to('/admin/employees/list')->with('error', 'Employee not found');
        }

        $data = [
            'title' => 'View Employee',
            'activeMenu' => 'employees',
            'employee' => $employee


        ];

        return view('admin/employees/view', $data);
    }

    public function changeStatus($id)
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $employeeId = $id;

        // Get the entire JSON body
        $json = $this->request->getJSON();
        $newStatus = $json->status ?? null;

        if (!$newStatus) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Status parameter is missing'
            ]);
        }

        $employeeModel = new \App\Models\EmployeeModel();
        $employee = $employeeModel->find($employeeId);

        if (!$employee) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Employee not found'
            ]);
        }

        // 🔒 REAL-WORLD RULE
        if ($employee['status'] === $newStatus) {
            return $this->response->setJSON([
                'status' => 'info',
                'message' => 'Employee is already ' . ucfirst($newStatus)
            ]);
        }

        // ✅ Update
        $result = $employeeModel->update($employeeId, [
            'status' => $newStatus
        ]);

        if (!$result) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update employee status'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Employee ' . ucfirst($newStatus) . ' successfully',
            'newStatus' => $newStatus
        ]);
    }

    public function edit($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);

        if (!$employee) {
            return redirect()->to('/admin/employees/list')->with('error', 'Employee not found');
        }

        $data = [
            'title' => 'Edit Employee',
            'activeMenu' => 'employees',
            'employee' => $employee
        ];

        return view('admin/employees/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);

        if (!$employee) {
            return redirect()->to('/admin/employees/list')->with('error', 'Employee not found');
        }

        // Get form input
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('password_confirmation');

        // Validate password confirmation
        if ($password !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match');
        }

        // Hash password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update employee
        $employeeModel->update($id, [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'phone'      => $this->request->getPost('phone'),
            'email'      => $this->request->getPost('email'),
            'password'   => $hashedPassword,
            'role'       => $this->request->getPost('role'),
            'status'     => $this->request->getPost('status'),
            // Store hashed password
        ]);

        return redirect()->to('admin/employees/list')
            ->with('success', 'Employee updated successfully');
    }

    /**
     * Show the assign employees to stations page (GET only)
     */
    public function assign()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $employeeModel = new EmployeeModel();
        $stationModel = new StationModel();

        // Build station list and map by id for easy lookup
        $stations = $stationModel->findAll();
        $stationsMap = [];
        foreach ($stations as $s) {
            $stationsMap[$s['id']] = $s;
        }

        // Get latest assignment per employee (by assigned_at desc)
        $assignModel = new AssignModel();
        $assignmentsRaw = $assignModel->orderBy('assigned_at', 'DESC')->findAll();
        $assignments = [];
        foreach ($assignmentsRaw as $a) {
            if (!isset($assignments[$a['employee_id']])) {
                $assignments[$a['employee_id']] = $a;
            }
        }

        return view('admin/employees/assign', [
            'title' => 'Assign Employees to Stations',
            'activeMenu' => 'employees',
            'employees' => $employeeModel->findAll(),
            'stations' => $stations,
            'stationsMap' => $stationsMap,
            'assignments' => $assignments
        ]);
    }

}