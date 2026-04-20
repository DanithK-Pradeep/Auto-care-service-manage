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
use App\Models\SparePartUsageModel;


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
    protected $spareUsageModel;


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
        $this->spareUsageModel        = new SparePartUsageModel();
    }

    public function index()
    {
        $layout = $this->request->isAJAX() ? 'employee/layout/blank' : 'employee/layout/empmain';
        $employeeId = session()->get('employee_id');
        $db = \Config\Database::connect();
        $today = date('Y-m-d');


        // 1. Completed count only
        $completedCount = $db->table('bookings')
            ->where('status', 'completed')
            ->countAllResults();

        // 2. Released count only
        $releasedCount = $db->table('bookings')
            ->where('status', 'released')
            ->countAllResults();

        // 3. Total finished (completed + released)
        $totalFinished = $completedCount + $releasedCount;

        // getting role for conditional data fetching
        $role = strtolower(trim(session()->get('employee_role') ?? ''));


        // Today's attendance record for the logged-in employee (if any)
        $attendanceModel = new AttendanceModel(); // Initialize the model
        $todayRecord = $attendanceModel->where('employee_id', $employeeId)
            ->where('work_date', $today)
            ->first();

        // Base Data Array 
        $data = [
            'title'       => 'My Dashboard',
            'activeMenu'  => 'dashboard',
            'todayRecord' => $todayRecord
        ];

        // Supervisor dashboard
        if ($role === 'supervisor') {

            // 1. Awaiting Approvals
            $data['awaitingCount'] = $db->table('booking_assignments')
                ->where('employee_id', $employeeId)
                ->where('status', 'assigned')
                ->countAllResults();

            // 2. In Progress bookings
            $data['inProgressCount'] = $db->table('booking_assignments ba')
                ->join('bookings b', 'b.id = ba.booking_id')
                ->where('ba.employee_id', $employeeId)
                ->where('ba.status', 'in_progress')
                ->where('b.status !=', 'completed')
                ->countAllResults();

            // 3. Ready for Billing
            $data['readyForBillingCount'] = $db->table('booking_assignments ba')
                ->join('bookings b', 'b.id = ba.booking_id')
                ->where('ba.employee_id', $employeeId)
                ->where('ba.status', 'in_progress')
                ->where('b.status', 'completed')
                ->countAllResults();

            $data['totalFinishedCount'] = $db->table('bookings')
                ->whereIn('status', ['completed', 'released'])
                ->countAllResults();

            //mechanic dashboard
        } else {

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
                ->countAllResults();

            // 2. Simple Strike Rate
            $totalWorkToday = $assignedCount + $inProgressCount + $handedOverToday;
            $strikeRate = ($totalWorkToday > 0) ? round(($handedOverToday / $totalWorkToday) * 100) : 0;

            // 3. Weekly Performance Data (Last 7 Days)
            $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
            $weeklyData = $db->table('booking_assignments')
                ->select('DATE(completed_at) as work_date, COUNT(id) as total_completed')
                ->where('employee_id', $employeeId)
                ->where('status', 'handed_over')
                ->where('completed_at >=', $sevenDaysAgo)
                ->groupBy('DATE(completed_at)')
                ->orderBy('work_date', 'ASC')
                ->get()->getResultArray();

            $chartLabels = [];
            $chartValues = [];
            foreach ($weeklyData as $row) {
                $chartLabels[] = date('D', strtotime($row['work_date']));
                $chartValues[] = $row['total_completed'];
            }

            // 4. "Up Next" Queue 
            $upNextQueue = $db->table('booking_assignments')
                ->select('booking_assignments.*, bookings.vehicle_model, stations.name as station_name')
                ->join('bookings', 'bookings.id = booking_assignments.booking_id')
                ->join('stations', 'stations.id = booking_assignments.station_id')
                ->where('booking_assignments.employee_id', $employeeId)
                ->where('booking_assignments.status', 'assigned')
                ->orderBy('booking_assignments.assigned_at', 'ASC')
                ->limit(5)
                ->get()->getResultArray();

            // Passing all data to the view
            $data['assignedCount']   = $assignedCount;
            $data['inProgressCount'] = $inProgressCount;
            $data['handedOverToday'] = $handedOverToday;
            $data['strikeRate']      = $strikeRate;
            $data['chartLabels']     = json_encode($chartLabels);
            $data['chartValues']     = json_encode($chartValues);
            $data['upNextQueue']     = $upNextQueue;
        }

        return view('employee/dashboard', $data, ['main_layout' => $layout]);
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
            'activeMenu'  => 'empdetail',
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

        // Determine layout based on request type
        $layout = $this->request->isAJAX() ? 'employee/layout/blank' : 'employee/layout/empmain';

        return view('employee/bookings', [
            'title'          => 'Bookings',
            'activeMenu'     => 'bookings',
            'role'           => session()->get('role'),
            'assignbookings' => $assignbookings,
            'main_layout'    => $layout,
        ]);
    }

    public function approve()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
        }

        $assignId = (int) $this->request->getPost('booking_assign_id');
        $assignment = $this->bookingAssignmentModel->select('id, booking_id, station_id, employee_id')->find($assignId);

        if (!$assignment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found']);
        }

        $startedAt = date('Y-m-d H:i:s');
        $db = \Config\Database::connect();

        $db->transStart();

        // 1) Update assignment
        $this->bookingAssignmentModel->update($assignId, [
            'status'     => 'in_progress',
            'started_at' => $startedAt,
            'updated_at' => $startedAt,
        ]);

        // 2)
        $mainBooking = $db->table('bookings')->where('id', $assignment['booking_id'])->get()->getRowArray();

        if ($mainBooking && $mainBooking['status'] !== 'completed' && $mainBooking['status'] !== 'final_inspection') {
            $this->bookingModel->update($assignment['booking_id'], [
                'status'     => 'in_progress',
                'updated_at' => $startedAt
            ]);
        }

        // 3) Check if job steps already exist for this booking+station, if not create from template
        $station       = $this->stationModel->find($assignment['station_id']);
        $stationTypeId = (int) ($station['station_type_id'] ?? 0);

        $exists = $this->jobStepsModel
            ->where('booking_id', $assignment['booking_id'])
            ->where('station_id', $assignment['station_id'])
            ->countAllResults();

        if ($exists === 0 && $stationTypeId > 0) {
            $templates = $this->typeStepModel->where('station_type_id', $stationTypeId)->orderBy('sequence_no', 'ASC')->findAll();
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

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Database error']);
        }

        // Redirect
        $role = strtolower(trim(session()->get('employee_role') ?? ''));
        $redirectUrl = ($role === 'supervisor') ? site_url('employee/supervisor/') : site_url('employee/services');

        return $this->response
            ->setHeader('HX-Trigger', 'refreshDashboard') // අපි දෙන නමක් (Custom Trigger)
            ->setJSON([
                'success' => true,
                'message' => 'Process successful!',
                'redirect' => $redirectUrl
            ]);
    }

    public function getBookingDetails($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Forbidden']);
        }

        // 1. මේ Assignment එකට අදාළ Booking සහ Station විස්තර ගන්නවා
        $booking = $this->bookingAssignmentModel
            ->select('booking_assignments.*, bookings.name, bookings.phone, bookings.vehicle_model, bookings.service, bookings.booking_date, bookings.final_notes')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->where('booking_assignments.id', $id)
            ->first();

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking not found']);
        }

        // 2. JS එකට පෙන්වන්න අවශ්‍ය අමතර දත්ත ටික දැන් Models හරහා Fetch කරගමු
        $assignmentHistory = $this->bookingAssignmentModel
            ->select('booking_assignments.*, stations.name as station_name, employees.name as employee_name')
            ->join('stations', 'stations.id = booking_assignments.station_id', 'left')
            ->join('employees', 'employees.id = booking_assignments.employee_id', 'left')
            ->where('booking_id', $booking['booking_id'])
            ->findAll();

        $jobSteps = $this->jobStepsModel
            ->select('job_steps.*, stations.name as station_name, employees.name as employee_name')
            ->join('stations', 'stations.id = job_steps.station_id', 'left')
            ->join('employees', 'employees.id = job_steps.assigned_employee_id', 'left')
            ->where('booking_id', $booking['booking_id'])
            ->findAll();

        $spareUsage = $this->spareUsageModel
            ->select('spare_usage.*, spare_parts.part_name, stations.name as station_name')
            ->join('spare_parts', 'spare_parts.id = spare_usage.spare_part_id', 'left')
            ->join('stations', 'stations.id = spare_usage.station_id', 'left')
            ->where('booking_id', $booking['booking_id'])
            ->findAll();

        // 3. දැන් මේ සියල්ලම JSON එකක් විදියට JS එකට යවනවා
        return $this->response->setJSON([
            'success'           => true,
            'booking'           => $booking,
            'serviceSummary'    => [
                'status'           => $booking['status'],
                'current_station'  => $booking['station_name'] ?? '-',
                'bay_no'           => $booking['bay_no'] ?? '1',
                'current_employee' => session()->get('name'),
                'started_at'       => $booking['started_at'] ?? '-'
            ],
            'assignmentHistory' => $assignmentHistory,
            'jobSteps'          => $jobSteps,
            'spareUsage'        => $spareUsage
        ]);
    }



    public function services()
    {
        $employeeId = session()->get('employee_id');

        // 1. Fetch Active Job (In-progress or just completed)
        $active = $this->bookingAssignmentModel
            ->select("booking_assignments.id AS assignment_id, booking_assignments.status AS assignment_status, booking_assignments.*, 
                  bookings.vehicle_model, bookings.service, bookings.id as b_id, bookings.booking_date, 
                  stations.name AS station_name, stations.bay_no, stations.station_type_id")
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->join('stations', 'stations.id = booking_assignments.station_id', 'left')
            ->where('booking_assignments.employee_id', $employeeId)
            ->whereIn('booking_assignments.status', ['in_progress', 'completed'])
            ->orderBy('booking_assignments.started_at', 'DESC')
            ->first();

        $steps = [];
        if ($active) {
            // Fetch step templates and current progress for the active station
            $templates = $this->typeStepModel
                ->where('station_type_id', $active['station_type_id'])
                ->orderBy('sequence_no', 'ASC')
                ->findAll();

            $progress = $this->jobStepsModel
                ->where('booking_id', $active['booking_id'])
                ->where('station_id', $active['station_id'])
                ->findAll();

            // Map progress by sequence for easier display
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

        // 2. Fetch data for Handover/Next Station Modal
        // We get all active stations except the current one
        $currentStationId = $active['station_id'] ?? 0;
        $stations = $this->stationModel
            ->where('status', 'active')
            ->where('id !=', $currentStationId)
            ->orderBy('name', 'ASC')
            ->findAll();

        // Fetch all employees for assignment
        $employees = $this->employeeModel
            ->select('id, first_name, last_name')
            ->orderBy('first_name', 'ASC')
            ->findAll();

        // 3. Determine Layout (Crucial for AJAX Navigation)
        // If it's an AJAX request, we use 'blank' layout to prevent Double Sidebars/Headers
        $layout = $this->request->isAJAX() ? 'employee/layout/blank' : 'employee/layout/empmain';

        return view('employee/services', [
            'title'          => 'Active Workspace',
            'activeMenu'     => 'services',
            'active'         => $active,
            'steps'          => $steps,
            'stations'       => $stations,
            'employees'      => $employees,
            'main_layout'    => $layout, // Passing the determined layout to the view
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

        $id = (int) $this->request->getPost('job_step_id');  // current assignment row id

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
        $notes        = trim((string) $this->request->getPost('note')); // form textarea name="note"

        // ✅ note optional, but station/employee required
        if ($assignmentId <= 0 || $bookingId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid assignment']);
        }

        if ($stationId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a station']);
        }

        if ($employeeId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select an employee']);
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
            return $this->response->setJSON(['success' => false, 'message' => 'Current assignment not found']);
        }

        // Optional safety: current assignment should be in progress/completed
        if (($current['status'] ?? '') !== 'completed') {
            return $this->response->setJSON(['success' => false, 'message' => 'Only completed assignment can hand over to next station']);
        }

        // Prevent assigning same station again
        if ((int)$current['station_id'] === $stationId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a different station']);
        }

        // 2) ✅ Check current station job steps all done/skipped
        $pendingSteps = $db->table('job_station_steps')
            ->where('booking_id', $bookingId)
            ->where('station_id', (int)$current['station_id'])
            ->whereNotIn('status', ['done', 'skipped', 'Done', 'Skipped'])
            ->countAllResults();

        if ($pendingSteps > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Complete all steps (Done/Skipped) before assigning next station']);
        }

        // 3) Next station must be active
        $station = $db->table('stations')
            ->where('id', $stationId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if (!$station) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected station is not active']);
        }

        // 4) Next employee must be active

        $nextEmployee = $db->table('employee_station es')
            ->select('e.role')
            ->join('employees e', 'e.id = es.employee_id', 'inner')
            ->where('es.station_id', $stationId)
            ->where('es.employee_id', $employeeId)
            ->where('e.status', 'active')
            ->get()
            ->getRowArray();

        if (!$nextEmployee) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected employee is not assigned to this station or is inactive']);
        }

        // 5) Prevent duplicate active/pending assignment for same booking + next station
        $alreadyExists = $db->table('booking_assignments')
            ->where('booking_id', $bookingId)
            ->where('station_id', $stationId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->countAllResults();

        if ($alreadyExists > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'This booking is already assigned to the selected station']);
        }

        // 6) Begin transaction
        $db->transStart();

        // Current station assignment -> handed_over
        $this->bookingAssignmentModel->update($assignmentId, [
            'status'       => 'handed_over',
            'completed_at' => $now,
            'updated_at'   => $now,
            'notes'       => $notes
        ]);

        // Next station assignment -> assigned
        $insertData = [
            'booking_id'  => $bookingId,
            'station_id'  => $stationId,
            'employee_id' => $employeeId,
            'status'      => 'assigned',
            'assigned_at' => $now,
            'updated_at'  => $now,
        ];
        $db->table('booking_assignments')->insert($insertData);

        $nextRole = strtolower(trim($nextEmployee['role'] ?? ''));

        if ($nextRole === 'inspector') {

            // DB update for inspector station
            $db->table('bookings')
                ->where('id', $bookingId)
                ->set('status', 'final_inspection')
                ->set('updated_at', $now)
                ->update();
        } elseif ($nextRole === 'supervisor') {

            // DB update for supervisor station
            $currentEmployeeId = session()->get('employee_id');

            $db->table('bookings')
                ->where('id', $bookingId)
                ->set('status', 'completed')
                ->set('completed_at', $now)
                ->set('completed_by', $currentEmployeeId)
                ->set('final_notes', $notes)
                ->set('updated_at', $now)
                ->update();
        }

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
