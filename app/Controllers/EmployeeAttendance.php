<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use App\Models\LeaveModel;
use CodeIgniter\Controller;

class EmployeeAttendance extends BaseController
{
    protected $attendanceModel;
    protected $leaveModel;
    protected $empModel;

    public function __construct()
    {
        // Models initialize කර ඇති නිසා සෑම method එකකදීම නැවත create කිරීමට අවශ්‍ය නැත
        $this->attendanceModel = new AttendanceModel();
        $this->leaveModel      = new LeaveModel();
        $this->empModel        = new EmployeeModel();
    }

    public function index()
    {
        $employeeId = session()->get('employee_id');

        // සේවකයා ලොග් වී නැත්නම් Login පේජ් එකට යොමු කරන්න
        if (!$employeeId) {
            return redirect()->to(site_url('login'));
        }

        $currentMonth = date('Y-m');

        // 1. Fetch Employee Profile & Rate
        $employee   = $this->empModel->find($employeeId);
        $hourlyRate = $employee['hourly_rate'] ?? 0;

        // 2. Attendance Data
        $todayRecord = $this->attendanceModel
            ->where('employee_id', $employeeId)
            ->where('work_date', date('Y-m-d'))
            ->first();

        $history = $this->attendanceModel
            ->where('employee_id', $employeeId)
            ->orderBy('work_date', 'DESC')
            ->limit(30)
            ->findAll();

        // 3. Leave History (දෙවතාවක් Fetch කිරීම මග හැරියෙමු)
        $leaveHistory = $this->leaveModel
            ->where('employee_id', $employeeId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // 4. Dynamic Salary Calculation
        $monthlyData = $this->attendanceModel
            ->selectSum('worked_hours')
            ->where('employee_id', $employeeId)
            ->like('work_date', $currentMonth, 'after')
            ->first();

        $totalHours      = $monthlyData['worked_hours'] ?? 0;
        $estimatedSalary = $totalHours * $hourlyRate;

        $data = [
            'title'               => 'My Attendance & HR',
            'todayRecord'         => $todayRecord,
            'history'             => $history,
            'leaveHistory'        => $leaveHistory,
            'hourlyRate'          => $hourlyRate,
            'totalHoursThisMonth' => $totalHours,
            'estimatedSalary'     => $estimatedSalary
        ];

        return view('employee/attendance_view', $data);
    }

    public function applyLeave()
    {
        $data = [
            'employee_id' => session()->get('employee_id'),
            'leave_date'  => $this->request->getPost('leave_date'),
            'leave_type'  => $this->request->getPost('leave_type'),
            'reason'      => $this->request->getPost('reason'),
            'status'      => 'pending'
        ];

        $this->leaveModel->insert($data);

        session()->setFlashdata('success', 'Leave request submitted successfully! Waiting for Admin approval.');
        return redirect()->to(site_url('employee/attendance'));
    }

    public function getFilteredHistory()
    {
        $employeeId = session()->get('employee_id');
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');

        $attendance = $this->attendanceModel
            ->where('employee_id', $employeeId)
            ->where('work_date >=', $start)
            ->where('work_date <=', $end)
            ->orderBy('work_date', 'DESC')
            ->findAll();

        $leaves = $this->leaveModel
            ->where('employee_id', $employeeId)
            ->where('leave_date >=', $start)
            ->where('leave_date <=', $end)
            ->orderBy('leave_date', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'attendance' => $attendance,
            'leaves'     => $leaves
        ]);
    }

    public function checkIn()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $employeeId = session()->get('employee_id');
        $today      = date('Y-m-d');
        $now        = date('Y-m-d H:i:s');

        $existing = $this->attendanceModel
            ->where('employee_id', $employeeId)
            ->where('work_date', $today)
            ->first();

        if ($existing) {
            return $this->response->setJSON([
                'status'  => 'info',
                'title'   => 'Already Active',
                'message' => 'You have already checked in today at ' . date('h:i A', strtotime($existing['check_in']))
            ]);
        }

        $data = [
            'employee_id' => $employeeId,
            'work_date'   => $today,
            'check_in'    => $now,
            'status'      => 'present'
        ];

        if ($this->attendanceModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'status'  => 'success',
                'message' => 'Check-in successful! Have a productive day.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'status'  => 'error',
            'message' => 'Failed to check in.'
        ]);
    }


    public function checkOut()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $employeeId = session()->get('employee_id');
        $today      = date('Y-m-d');
        $now        = date('Y-m-d H:i:s');

        $record = $this->attendanceModel
            ->where('employee_id', $employeeId)
            ->where('work_date', $today)
            ->first();

        if ($record && empty($record['check_out'])) {
            $secondsDiff = strtotime($now) - strtotime($record['check_in']);
            $hoursWorked = round($secondsDiff / 3600, 2);

            $updateData = [
                'check_out'    => $now,
                'worked_hours' => $hoursWorked,
                'status'       => 'present'
            ];

            if ($this->attendanceModel->update($record['id'], $updateData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'status'  => 'success',
                    'message' => 'Shift ended! You worked ' . $hoursWorked . ' hours today.'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'status'  => 'error',
                'message' => 'Unable to process check-out.'
            ]);
        }
    }
}
