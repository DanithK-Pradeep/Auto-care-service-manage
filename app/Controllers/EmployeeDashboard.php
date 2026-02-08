<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\AssignModel;

class EmployeeDashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('employee_logged_in')) {
            return redirect()->to(site_url('employee/login'));
        }

        return view('employee/dashboard', [
            'title' => 'Employee Dashboard'
        ]);
    }


public function details()
{
    if (!session()->get('employee_logged_in')) {
        return redirect()->to(site_url('employee/login'));
    }

    $employeeId = session()->get('employee_id');

    $employeeModel = new EmployeeModel();
    $employee = $employeeModel->find($employeeId);

    $assignModel = new AssignModel();

    // ✅ Assignment history + Station details
    $assignments = $assignModel
        ->select('employee_station.*, stations.name as station_name, stations.bay_no, stations.status as station_status')
        ->join('stations', 'stations.id = employee_station.station_id', 'left')
        ->where('employee_station.employee_id', $employeeId)
        ->orderBy('employee_station.assigned_at', 'DESC')
        ->findAll();

    return view('employee/employeedetail', [
        'title' => 'Employee Details',
        'employee' => $employee,
        'assignments' => $assignments,
    ]);
}

}
