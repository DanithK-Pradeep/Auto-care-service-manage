<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\AssignModel;
use App\Models\BookingAssignmentModel;
use App\Models\BookingModel;
use App\Models\StationModel;
use App\Models\StationTypeStepModel;
use App\Models\JobStationModel;
use App\Models\AttendanceModel;


class EmployeeDashboard extends BaseController
{
    // Professional approach: Initialize models in the constructor
    protected $employeeModel;
    protected $assignModel;
    protected $bookingAssignmentModel;
    protected $bookingModel;
    protected $jobStepsModel;
    protected $stationModel;
    protected $typeStepModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->employeeModel          = new EmployeeModel();
        $this->assignModel            = new AssignModel();
        $this->bookingAssignmentModel = new BookingAssignmentModel();
        $this->bookingModel           = new BookingModel();
        $this->jobStepsModel          = new JobStationModel();
        $this->stationModel           = new StationModel();
        $this->typeStepModel          = new StationTypeStepModel();
        $this->attendanceModel        = new AttendanceModel();
    }

    public function index()
    {
        $employeeId = session()->get('employee_id');
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // 1. Top Row Quick Stats (KPIs)
        $assignedCount = $db->table('booking_assignments')
            ->where('employee_id', $employeeId)
            ->where('status', 'assigned')
            ->countAllResults();

        $inProgressCount = $db->table('booking_assignments')
            ->where('employee_id', $employeeId)
            ->whereIn('status', ['in_progress', 'completed']) // Currently active
            ->countAllResults();

        $handedOverToday = $db->table('booking_assignments')
            ->where('employee_id', $employeeId)
            ->where('status', 'handed_over')
            ->like('completed_at', $today, 'after') // Matches anything completed today
            ->countAllResults();

        $attendanceModel = new AttendanceModel();
        $todayRecord = $attendanceModel->where('employee_id', $employeeId)
            ->where('work_date', date('Y-m-d'))
            ->first();



        // 2. Simple Strike Rate (Efficiency for Today)
        // Formula: (Completed Work / Total Work Given Today) * 100
        $totalWorkToday = $assignedCount + $inProgressCount + $handedOverToday;
        $strikeRate = ($totalWorkToday > 0) ? round(($handedOverToday / $totalWorkToday) * 100) : 0;

        // 3. Weekly Performance Data (Last 7 Days for the Bar Chart)
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        $weeklyData = $db->table('booking_assignments')
            ->select('DATE(completed_at) as work_date, COUNT(id) as total_completed')
            ->where('employee_id', $employeeId)
            ->where('status', 'handed_over')
            ->where('completed_at >=', $sevenDaysAgo)
            ->groupBy('DATE(completed_at)')
            ->orderBy('work_date', 'ASC')
            ->get()->getResultArray();

        // Format the weekly data for Chart.js
        $chartLabels = [];
        $chartValues = [];
        foreach ($weeklyData as $row) {
            $chartLabels[] = date('D', strtotime($row['work_date'])); // Converts to 'Mon', 'Tue'
            $chartValues[] = $row['total_completed'];
        }

        // 4. "Up Next" Queue (To show waiting vehicles directly on the dashboard)
        $upNextQueue = $db->table('booking_assignments')
            ->select('booking_assignments.*, bookings.vehicle_model, stations.name as station_name')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id')
            ->join('stations', 'stations.id = booking_assignments.station_id')
            ->where('booking_assignments.employee_id', $employeeId)
            ->where('booking_assignments.status', 'assigned')
            ->orderBy('booking_assignments.assigned_at', 'ASC')
            ->limit(5) // Only show the next 5 vehicles
            ->get()->getResultArray();

        // Pass everything to the view
        $data = [
            'title'           => 'My Dashboard',
            'assignedCount'   => $assignedCount,
            'todayRecord'     => $todayRecord,
            'inProgressCount' => $inProgressCount,
            'handedOverToday' => $handedOverToday,
            'strikeRate'      => $strikeRate,
            'chartLabels'     => json_encode($chartLabels), // JSON encode for JavaScript
            'chartValues'     => json_encode($chartValues), // JSON encode for JavaScript
            'upNextQueue'     => $upNextQueue
        ];

        return view('employee/dashboard', $data);
    }


    public function details()
    {
        $employeeId = session()->get('employee_id');
        $employee   = $this->employeeModel->find($employeeId);

        $assignments = $this->assignModel
            ->select('employee_station.*, stations.name as station_name, stations.bay_no, stations.status as station_status')
            ->join('stations', 'stations.id = employee_station.station_id', 'left')
            ->where('employee_station.employee_id', $employeeId)
            ->orderBy('employee_station.assigned_at', 'DESC')
            ->findAll();

        return view('employee/employeedetail', [
            'title'       => 'Employee Details',
            'employee'    => $employee,
            'assignments' => $assignments,
        ]);
    }

    public function bookings()
    {
        $employeeId = session()->get('employee_id');

        $assignbookings = $this->bookingAssignmentModel
            ->select('booking_assignments.*, bookings.vehicle_model, bookings.service, bookings.booking_date, stations.name as station_name')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->join('stations', 'stations.id = booking_assignments.station_id', 'left')
            ->where('booking_assignments.employee_id', $employeeId)
            ->orderBy('booking_assignments.assigned_at', 'DESC')
            ->findAll();

        return view('employee/bookings', [
            'title'          => 'Bookings',
            'assignbookings' => $assignbookings,
        ]);
    }

    public function approve()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
        }

        $assignId = (int) $this->request->getPost('booking_assign_id');
        if (!$assignId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid booking ID']);
        }

        $assignment = $this->bookingAssignmentModel->find($assignId);
        if (!$assignment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found']);
        }

        $startedAt = date('Y-m-d H:i:s');
        $db = \Config\Database::connect();

        // Start Transaction
        $db->transStart();

        // 1) Update THIS SPECIFIC station assignment status to in_progress
        $this->bookingAssignmentModel->update($assignId, [
            'status'     => 'in_progress',
            'started_at' => $startedAt,
            'updated_at' => $startedAt,
        ]);

        // 2) Update main booking status 
        // We use a WHERE check to ensure we don't cause a collision if it's already in_progress
        $this->bookingModel->update($assignment['booking_id'], [
            'status'     => 'in_progress',
            'updated_at' => $startedAt
        ]);

        // 3) Generate Job Steps FOR THIS STATION
        $station       = $this->stationModel->find($assignment['station_id']);
        $stationTypeId = (int) ($station['station_type_id'] ?? 0);

        // CRITICAL: Check if steps exist FOR THIS SPECIFIC BOOKING AND THIS SPECIFIC STATION
        $exists = $this->jobStepsModel
            ->where('booking_id', $assignment['booking_id'])
            ->where('station_id', $assignment['station_id'])
            ->countAllResults();

        if ($exists === 0 && $stationTypeId > 0) {
            $templates = $this->typeStepModel
                ->where('station_type_id', $stationTypeId)
                ->orderBy('sequence_no', 'ASC')
                ->findAll();

            if (!empty($templates)) {
                $batch = array_map(function ($t) use ($assignment, $startedAt) {
                    return [
                        'booking_id'           => $assignment['booking_id'],
                        'station_id'           => $assignment['station_id'],
                        'sequence_no'          => $t['sequence_no'],
                        'status'               => 'pending',
                        'assigned_employee_id' => $assignment['employee_id'],
                        'created_at'           => $startedAt,
                        'updated_at'           => $startedAt,
                    ];
                }, $templates);

                $this->jobStepsModel->insertBatch($batch);
            }
        }

        $db->transComplete();

        // Debugging the actual error
        if ($db->transStatus() === false) {
            // Log the actual error to your writable/logs folder so you can see the SQL error
            log_message('error', 'Database Error: ' . json_encode($db->error()));

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database error: ' . $db->error()['message']
            ]);
        }

        return $this->response->setJSON([
            'success'      => true,
            'message'      => 'Booking approved and bay steps generated.',
            'redirect_url' => site_url('employee/services')
        ]);
    }

    public function getBookingDetails($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
        }

        $booking = $this->bookingAssignmentModel
            ->select('booking_assignments.*, bookings.name, bookings.phone, bookings.vehicle_model, bookings.service, bookings.booking_date')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->where('booking_assignments.id', $id)
            ->first();

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found']);
        }

        return $this->response->setJSON(['success' => true, 'booking' => $booking]);
    }



    public function services()
    {
        $employeeId = session()->get('employee_id');

        // 1. Fetch Active Job
        $active = $this->bookingAssignmentModel
            ->select("booking_assignments.id AS assignment_id, booking_assignments.status AS assignment_status, booking_assignments.*, 
                      bookings.vehicle_model, bookings.service, bookings.booking_date, 
                      stations.name AS station_name, stations.bay_no, stations.station_type_id")
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->join('stations', 'stations.id = booking_assignments.station_id', 'left')
            ->where('booking_assignments.employee_id', $employeeId)
            ->whereIn('booking_assignments.status', ['in_progress', 'completed'])
            ->orderBy('booking_assignments.started_at', 'DESC')
            ->first();

        $steps = [];
        if ($active) {
            $templates = $this->typeStepModel->where('station_type_id', $active['station_type_id'])->orderBy('sequence_no', 'ASC')->findAll();
            $progress  = $this->jobStepsModel->where('booking_id', $active['booking_id'])->where('station_id', $active['station_id'])->findAll();

            $map = [];
            foreach ($progress as $p) {
                $map[$p['sequence_no']] = $p;
            }

            foreach ($templates as $t) {
                $p = $map[$t['sequence_no']] ?? null;
                $steps[] = [
                    'title'       => $t['title'],
                    'sequence_no' => $t['sequence_no'],
                    'job_step_id' => $p['id'] ?? null,
                    'status'      => $p['status'] ?? 'pending',
                ];
            }
        }

        // 2. Fetch data for Handover Modal
        $stations  = $this->stationModel->where('status', 'active')->where('id !=', $active['station_id'] ?? 0)->orderBy('name', 'ASC')->findAll();
        $employees = $this->employeeModel->select('id, first_name, last_name')->orderBy('first_name', 'ASC')->findAll();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'active' => $active, 'steps' => $steps, 'stations' => $stations, 'employees' => $employees]);
        }

        return view('employee/services', [
            'title' => 'Active Workspace',
            'active' => $active,
            'steps' => $steps,
            'stations' => $stations,
            'employees' => $employees
        ]);
    }


    public function startProcess()
    {
        $id = $this->request->getPost('assignment_id');
        $assign = $this->bookingAssignmentModel->find($id);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);

        $now = date('Y-m-d H:i:s');
        if (empty($assign['started_at'])) {
            $this->bookingAssignmentModel->update($id, ['started_at' => $now, 'updated_at' => $now]);
            $this->bookingModel->update($assign['booking_id'], ['status' => 'in_progress', 'updated_at' => $now]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Process started', 'started_at' => $now]);
    }

    public function finishProcess()
    {
        $id = (int)$this->request->getPost('assignment_id');
        $assign = $this->bookingAssignmentModel->find($id);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);

        // Check for pending steps
        $pending = $this->jobStepsModel->where('booking_id', $assign['booking_id'])->where('station_id', $assign['station_id'])
            ->whereIn('status', ['pending', 'in_progress'])->countAllResults();

        if ($pending > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Complete all steps first']);
        }

        $now = date('Y-m-d H:i:s');
        // BUG FIX: Change status to 'completed' so it leaves the active list
        $this->bookingAssignmentModel->update($id, ['status' => 'completed', 'completed_at' => $now, 'updated_at' => $now]);

        return $this->response->setJSON(['success' => true, 'message' => 'Station work finished']);
    }

    public function doneJobStep()
    {

        $id = (int) $this->request->getPost('job_step_id');  // ✅ Correct

        if (!$id) {
            $json = $this->request->getJSON(true);
            $id = $json['job_step_id'] ?? null;
        }

        $id = (int)$id;
        if ($id <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid step']);
        }
        $jobStepsModel = new JobStationModel();
        $step = $jobStepsModel->find($id);
        if (!$step) return $this->response->setJSON(['success' => false, 'message' => 'Step not found']);

        $now = date('Y-m-d H:i:s');

        $jobStepsModel->update($id, [
            'status'     => 'done',
            'end_time'   => $now,
            'updated_at' => $now
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Step marked done']);
    }


    public function skipJobStep()
    {


        $id = $this->request->getPost('job_step_id');
        if (!$id) return $this->response->setJSON(['success' => false, 'message' => 'Invalid step']);

        $jobStepsModel = new JobStationModel();
        $step = $jobStepsModel->find($id);
        if (!$step) return $this->response->setJSON(['success' => false, 'message' => 'Step not found']);

        $now = date('Y-m-d H:i:s');

        $jobStepsModel->update($id, [
            'status'     => 'skipped',
            'end_time'   => $now,
            'updated_at' => $now
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Step skipped']);
    }



    public function loadstations()

    {


        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $stationModel = new StationModel();

        $builder = $stationModel
            ->select('id, station_type_id, name, bay_no, status, capacity')
            ->where('status', 'active');

        // OPTIONAL: station type filter (if you pass station_type_id from frontend)
        $stationTypeId = (int) $this->request->getPost('station_type_id');
        if ($stationTypeId > 0) {
            $builder->where('station_type_id', $stationTypeId);
        }

        $stations = $builder
            ->orderBy('name', 'ASC')
            ->orderBy('bay_no', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'stations' => $stations
        ]);
    }

    public function loadEmployees()
    {


        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $stationId = (int)$this->request->getGet('station_id');
        if ($stationId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Station is required']);
        }

        $db = \Config\Database::connect();

        $employees = $db->table('employee_station es')
            ->select('e.id, e.first_name, e.last_name')
            ->join('employees e', 'e.id = es.employee_id', 'inner')
            ->where('es.station_id', $stationId)
            ->where('e.status', 'active')
            ->groupBy('e.id')
            ->orderBy('e.first_name', 'ASC')
            ->orderBy('e.last_name', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'employees' => $employees
        ]);
    }

    public function assignNext()
    {


        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $assignmentId = (int) $this->request->getPost('assignment_id'); // current assignment row id
        $bookingId    = (int) $this->request->getPost('booking_id');
        $stationId    = (int) $this->request->getPost('station_id');    // next station
        $employeeId   = (int) $this->request->getPost('employee_id');   // next employee
        $notes        = trim((string) $this->request->getPost('note')); // form textarea name="note" (DB column = notes)

        // ✅ note optional, but station/employee required
        if ($assignmentId <= 0 || $bookingId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid assignment'
            ]);
        }

        if ($stationId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select a station'
            ]);
        }

        if ($employeeId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select an employee'
            ]);
        }

        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // 1) Current assignment row check
        $current = $db->table('booking_assignments')
            ->where('id', $assignmentId)
            ->where('booking_id', $bookingId)
            ->get()
            ->getRowArray();

        if (!$current) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Current assignment not found'
            ]);
        }

        // Optional safety: current assignment should be in progress
        if (($current['status'] ?? '') !== 'completed') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only completed assignment can hand over to next station'
            ]);
        }

        // Prevent assigning same station again
        if ((int)$current['station_id'] === $stationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select a different station'
            ]);
        }

        // 2) ✅ Check current station job steps all done/skipped
        // Table from your screenshot: job_station_steps
        $pendingSteps = $db->table('job_station_steps')
            ->where('booking_id', $bookingId)
            ->where('station_id', (int)$current['station_id'])
            ->whereNotIn('status', ['done', 'skipped', 'Done', 'Skipped']) // case-safe
            ->countAllResults();

        if ($pendingSteps > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Complete all steps (Done/Skipped) before assigning next station'
            ]);
        }

        // 3) Next station must be active
        $station = $db->table('stations')
            ->where('id', $stationId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if (!$station) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Selected station is not active'
            ]);
        }

        // 4) ✅ Employee must belong to selected station + be active
        $allowedEmployee = $db->table('employee_station es')
            ->join('employees e', 'e.id = es.employee_id', 'inner')
            ->where('es.station_id', $stationId)
            ->where('es.employee_id', $employeeId)
            ->where('e.status', 'active')
            ->countAllResults();

        if ($allowedEmployee <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Selected employee is not assigned to this station or is inactive'
            ]);
        }

        // 5) Prevent duplicate active/pending assignment for same booking + next station
        $alreadyExists = $db->table('booking_assignments')
            ->where('booking_id', $bookingId)
            ->where('station_id', $stationId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->countAllResults();

        if ($alreadyExists > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This booking is already assigned to the selected station'
            ]);
        }

        // 6) ✅ Transaction: complete current + create next pending
        $db->transStart();

        // Current station assignment -> complete
        $this->bookingAssignmentModel->update($assignmentId, [
            'status' => 'handed_over',
            'completed_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        // Next station assignment -> pending
        $insertData = [
            'booking_id'   => $bookingId,
            'station_id'   => $stationId,
            'employee_id'  => $employeeId,
            'status'       => 'assigned',
            'notes'        => $notes,
            'assigned_at'  => $now,
            'updated_at'   => $now,

        ];

        $db->table('booking_assignments')->insert($insertData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to assign next station'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Next station assigned successfully.'
        ]);
    }

    public function viewBookingData($bookingId)
    {


        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $bookingId = (int) $bookingId;
        if (!$bookingId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid booking id']);
        }

        $db = db_connect();


        $booking = $db->table('bookings')->where('id', $bookingId)->get()->getRowArray();

        // If not found, maybe it's booking_assignments.id
        if (!$booking) {
            $row = $db->table('booking_assignments')
                ->select('booking_id')
                ->where('id', $bookingId)
                ->get()
                ->getRowArray();

            if ($row && !empty($row['booking_id'])) {
                $bookingId = (int)$row['booking_id'];
                $booking = $db->table('bookings')->where('id', $bookingId)->get()->getRowArray();
            }
        }

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found']);
        }

        // booking_assignments history
        $assignmentHistory = $db->table('booking_assignments ba')
            ->select("
            ba.id, ba.booking_id, ba.station_id, ba.employee_id, ba.status, ba.notes,
            ba.assigned_at, ba.started_at, ba.completed_at, ba.updated_at,
            s.name as station_name, s.bay_no,
            CONCAT(e.first_name,' ',e.last_name) as employee_name
        ")
            ->join('stations s', 's.id = ba.station_id', 'left')
            ->join('employees e', 'e.id = ba.employee_id', 'left')
            ->where('ba.booking_id', $bookingId)
            ->orderBy('ba.assigned_at', 'ASC')
            ->get()
            ->getResultArray();

        // summary = latest assignment row
        $current = null;
        if (!empty($assignmentHistory)) {
            $current = end($assignmentHistory) ?: null;
        }

        $serviceSummary = [
            'status'           => $current['status'] ?? '-',
            'current_station'  => $current['station_name'] ?? '-',
            'bay_no'           => $current['bay_no'] ?? '-',
            'current_employee' => $current['employee_name'] ?? '-',
            'started_at'       => $current['started_at'] ?? '-',
            'finished_at'      => $current['completed_at'] ?? '-',
        ];

        // job_station_steps
        $jobSteps = $db->table('job_station_steps js')
            ->select("
            js.id, js.booking_id, js.station_id, js.sequence_no, js.status,
            js.assigned_employee_id, js.end_time,
            s.name as station_name, s.bay_no,
            CONCAT(e.first_name,' ',e.last_name) as employee_name
        ")
            ->join('stations s', 's.id = js.station_id', 'left')
            ->join('employees e', 'e.id = js.assigned_employee_id', 'left')
            ->where('js.booking_id', $bookingId)
            ->orderBy('js.station_id', 'ASC')
            ->orderBy('js.sequence_no', 'ASC')
            ->get()
            ->getResultArray();

        // spare_part_usages + spare_parts.name
        $spareUsage = $db->table('spare_part_usages spu')
            ->select("
            spu.id, spu.booking_id, spu.spare_part_id, spu.station_id, spu.employee_id,
            spu.qty, spu.created_at,
            sp.name as part_name,
            s.name as station_name, s.bay_no
        ")
            ->join('spare_parts sp', 'sp.id = spu.spare_part_id', 'left')
            ->join('stations s', 's.id = spu.station_id', 'left')
            ->where('spu.booking_id', $bookingId)
            ->orderBy('spu.created_at', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success'           => true,
            'booking'           => $booking,
            'serviceSummary'    => $serviceSummary,
            'assignmentHistory' => $assignmentHistory,
            'jobSteps'          => $jobSteps,
            'spareUsage'        => $spareUsage,
        ]);
    }
}
