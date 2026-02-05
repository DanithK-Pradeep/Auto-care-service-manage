<?php

namespace App\Controllers;

use App\Models\AssignModel;
use App\Models\EmployeeModel;
use App\Models\StationModel;
use Config\Services;

class AdminAssign extends BaseController
{
    /**
     * Handle assignment POST (AJAX or normal form submit)
     */
    public function store()
    {
        if (!session()->get('admin_logged_in')) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }
            return redirect()->to('/admin/login');
        }

        $employeeId = $this->request->getPost('employee_id');
        $stationId  = $this->request->getPost('station_id');

        // Check if employee ID and station ID are provided
        if (!$employeeId || !$stationId) {
            $resp = ['success' => false, 'message' => 'Please select both employee and station'];
            if ($this->request->isAJAX()) return $this->response->setContentType('application/json')->setJSON($resp);
            return redirect()->back()->withInput()->with('error', $resp['message']);
        }

        try {
            // Fetch employee information
            $employeeModel = new EmployeeModel();
            $employee = $employeeModel->find($employeeId);
            $stationModel = new StationModel();
            $station = $stationModel->find($stationId);

            // Check if the employee exists and if their status is 'inactive'
            if (!$employee) {
                $resp = ['success' => false, 'message' => 'Employee not found'];
                if ($this->request->isAJAX()) return $this->response->setContentType('application/json')->setJSON($resp);
                return redirect()->back()->withInput()->with('error', $resp['message']);
            }

            if ($employee['status'] === 'inactive') {
                $resp = ['success' => false, 'message' => 'Inactive employees cannot be assigned to a station'];
                if ($this->request->isAJAX()) return $this->response->setContentType('application/json')->setJSON($resp);
                return redirect()->back()->withInput()->with('error', $resp['message']);
            }

            if (!$station) {
                $resp = ['success' => false, 'message' => 'Station not found'];
                if ($this->request->isAJAX()) return $this->response->setContentType('application/json')->setJSON($resp);
                return redirect()->back()->withInput()->with('error', $resp['message']);
            }

            if ($station['status'] === 'inactive' || $station['status'] === 'maintenance') {
                $resp = ['success' => false, 'message' => $station['status'] . ' stations cannot be assigned to an employee'];
                if ($this->request->isAJAX()) return $this->response->setContentType('application/json')->setJSON($resp);
                return redirect()->back()->withInput()->with('error', $resp['message']);
            }

            // Proceed with assignment if employee is active
            $assignModel = new AssignModel();
            $data = [
                'employee_id' => $employeeId,
                'station_id'  => $stationId,

            ];

            $insertId = $assignModel->insert($data);
            if ($insertId) {
                // Fetch station info to return for client-side update
                $stationModel = new StationModel();
                $station = $stationModel->find($stationId);


                $resp = [
                    'success' => true,
                    'message' => 'Employee assigned to station successfully',
                    'assignment' => [
                        'employee_id' => (int) $employeeId,
                        'station_id' => (int) $stationId,
                        'station_name' => $station['name'] ?? null,
                        'bay_no' => $station['bay_no'] ?? null,
                    ]
                ];
            } else {
                $resp = ['success' => false, 'message' => 'Failed to assign employee to station'];
            }
        } catch (\Exception $e) {
            $resp = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }

        // Return AJAX response
        if ($this->request->isAJAX()) {
            return $this->response->setContentType('application/json')->setJSON($resp);
        }

        // For normal form submission
        if ($resp['success']) {
            return redirect()->back()->with('success', $resp['message']);
        }
        return redirect()->back()->withInput()->with('error', $resp['message']);
    }

    public function getEmployeeDetails($employeeId)
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $employeeModel = new \App\Models\EmployeeModel();
        $assignModel   = new \App\Models\AssignModel();

        $employee = $employeeModel->find($employeeId);

        if (!$employee) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Employee not found'
            ]);
        }

        // ✅ Full assignment history (latest first) + station details
        $assignments = $assignModel
            ->select('employee_station.*, stations.name as station_name, stations.bay_no, stations.status as station_status')
            ->join('stations', 'stations.id = employee_station.station_id', 'left')
            ->where('employee_station.employee_id', $employeeId)
            ->orderBy('employee_station.assigned_at', 'DESC')
            ->findAll();

        // ✅ Decide which row is "current"
        // Option A (recommended): latest assignment is current
        $currentAssignmentId = !empty($assignments) ? $assignments[0]['id'] : null;

        // Option B (if you want primary assignment to be current):
        // $primary = array_values(array_filter($assignments, fn($a) => (int)$a['is_primary'] === 1));
        // $currentAssignmentId = !empty($primary) ? $primary[0]['id'] : (!empty($assignments) ? $assignments[0]['id'] : null);

        return $this->response->setJSON([
            'success' => true,
            'employee' => $employee,
            'assignments' => $assignments,
            'current_assignment_id' => $currentAssignmentId
        ]);
    }
}
