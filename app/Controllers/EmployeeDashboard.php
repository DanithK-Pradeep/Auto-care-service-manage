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


class EmployeeDashboard extends BaseController
{
    public function index()
    {
        // Check if employee is logged in
        if (!session()->get('employee_logged_in')) {
            return redirect()->to(site_url('employee/login'));
        }

        return view('employee/dashboard', [
            'title' => 'Employee Dashboard'
        ]);
    }


    public function details()
    {   // Check if employee is logged in
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

    public function bookings()
    {
        if (!session()->get('employee_logged_in')) {
            return redirect()->to(site_url('employee/login'));
        }

        $employeeId = session()->get('employee_id');

        $bookingAssignmentModel = new BookingAssignmentModel();

        // ✅ Booking history + Station details
        $assignbookings = $bookingAssignmentModel
            ->select('booking_assignments.*, bookings.vehicle_model, bookings.service, bookings.booking_date, stations.name as station_name')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->join('stations', 'stations.id = booking_assignments.station_id', 'left')
            ->where('booking_assignments.employee_id', $employeeId)
            ->orderBy('booking_assignments.assigned_at', 'DESC')
            ->findAll();


        // ✅ Count total bookings for the employee
        $bookingModel = new BookingModel();
        $bookings = $bookingModel
            ->select('bookings.*')
            ->join('booking_assignments', 'booking_assignments.booking_id = bookings.id', 'left')
            ->whereIn('booking_assignments.status', ['assigned', 'in_progress'])
            ->where('booking_assignments.employee_id', $employeeId)
            ->countAllResults();

        return view('employee/bookings', [
            'title' => 'Bookings',
            'assignbookings' => $assignbookings,
            'bookings' => $bookings,
        ]);
    }

    public function approve()
    {
        // 1. Check if employee is logged in
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        // 2. Get booking ID from POST request
        $bookingId = $this->request->getPost('booking_id');
        if (!$bookingId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid booking'
            ]);
        }

        // 3. Load the BookingAssignmentModel
        $bookingAssignmentModel = new BookingAssignmentModel();
        $bookingModel = new BookingModel();



        // 4. Fetch the booking record from database
        $booking = $bookingAssignmentModel->find($bookingId);
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        // 5. Prevent duplicate approval
        if ($booking['status'] === 'in_progress') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking is already in progress'
            ]);
        }

        // 6. Set the start time
        $startedAt = date('Y-m-d H:i:s');

        // 7. Update the booking status in database
        $updated = $bookingAssignmentModel->update($bookingId, [
            'status' => 'in_progress',
            'started_at' => $startedAt
        ]);
        // Also update in bookings table
        $updated = $bookingModel->update($booking['booking_id'], [
            'status' => 'in_progress',
            'updated_at' => $startedAt
        ]);

        if (!$updated) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update booking'
            ]);
        }

        // 8. Return success response for toast message

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Booking approved successfully',
            'status' => 'In progress',
            'started_at' => $startedAt
        ]);

        $jobStepsModel = new JobStationModel();
        $stationModel  = new StationModel();
        $typeStepModel = new StationTypeStepModel();

        $station = $stationModel->find($booking['station_id']);
        $stationTypeId = $station['station_type_id'] ?? null;

        $exists = $jobStepsModel
            ->where('booking_id', $booking['booking_id'])
            ->where('station_id', $booking['station_id'])
            ->countAllResults();

        if ($exists == 0 && $stationTypeId) {
            $templates = $typeStepModel
                ->where('station_type_id', $stationTypeId)
                ->orderBy('sequence_no', 'ASC')
                ->findAll();

            foreach ($templates as $t) {
                $jobStepsModel->insert([
                    'booking_id' => $booking['booking_id'],
                    'station_id' => $booking['station_id'],
                    'sequence_no' => $t['sequence_no'],
                    'status' => 'pending',
                    'assigned_employee_id' => session()->get('employee_id'),
                    'start_time' => null,
                    'end_time' => null,
                    'notes' => null,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function getBookingDetails($id)

    {
        // Check if employee is logged in
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $bookingAssignmentModel = new BookingAssignmentModel();
        // Fetch booking details
        $booking = $bookingAssignmentModel
            ->select('booking_assignments.*, 
                  bookings.name,
                  bookings.phone,
                  bookings.vehicle_model,
                  bookings.service,
                  bookings.booking_date')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->where('booking_assignments.id', $id)
            ->first();
        // Check if booking exists
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }
        // Return booking details as JSON
        return $this->response->setJSON([
            'success' => true,
            'booking' => $booking
        ]);
    }


    public function services()
    {
        if (!session()->get('employee_logged_in')) {
            return redirect()->to(site_url('employee/login'));
        }

        $employeeId = session()->get('employee_id');

        $assignmentModel = new BookingAssignmentModel();
        $jobStepsModel   = new JobStationModel();
        $typeStepModel   = new StationTypeStepModel();

        $active = $assignmentModel
            ->select('booking_assignments.*,
                  bookings.vehicle_model, bookings.service, bookings.booking_date, bookings.status as booking_status,
                  stations.name as station_name, stations.bay_no, stations.station_type_id')
            ->join('bookings', 'bookings.id = booking_assignments.booking_id', 'left')
            ->join('stations', 'stations.id = booking_assignments.station_id', 'left')
            ->where('booking_assignments.employee_id', $employeeId)
            ->where('booking_assignments.status', 'in_progress')
            ->orderBy('booking_assignments.started_at', 'DESC')
            ->first();

        $steps = [];

        if ($active) {
            $templates = $typeStepModel
                ->where('station_type_id', $active['station_type_id'])
                ->orderBy('sequence_no', 'ASC')
                ->findAll();

            $progress = $jobStepsModel
                ->where('booking_id', $active['booking_id'])
                ->where('station_id', $active['station_id'])
                ->orderBy('sequence_no', 'ASC')
                ->findAll();

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

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'active'  => $active,
                'steps'   => $steps
            ]);
        }

        return view('employee/services', [
            'title'  => 'Services',
            'active' => $active,
            'steps'  => $steps
        ]);
    }


    public function startProcess()
    {
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $assignmentId = $this->request->getPost('assignment_id');
        if (!$assignmentId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid assignment']);
        }

        $assignmentModel = new BookingAssignmentModel();
        $bookingModel    = new BookingModel();

        $assign = $assignmentModel->find($assignmentId);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found']);

        $now = date('Y-m-d H:i:s');

        // only set if empty
        if (empty($assign['started_at'])) {
            $assignmentModel->update($assignmentId, [
                'started_at' => $now,
                'updated_at' => $now,
            ]);
            $bookingModel->update($assign['booking_id'], [
                'status'     => 'in_progress',
                'updated_at' => $now
            ]);
        }

        return $this->response->setJSON([
            'success'    => true,
            'message'    => 'Process started',
            'started_at' => $assign['started_at'] ?: $now
        ]);
    }


    public function finishProcess()
    {
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $assignmentId = $this->request->getPost('assignment_id');
        if (!$assignmentId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid assignment']);
        }

        $assignmentModel = new BookingAssignmentModel();
        $bookingModel    = new BookingModel();
        $jobStepsModel   = new JobStationModel();

        $assign = $assignmentModel->find($assignmentId);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found']);

        // check all steps completed
        $remaining = $jobStepsModel
            ->where('booking_id', $assign['booking_id'])
            ->where('station_id', $assign['station_id'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->countAllResults();

        if ($remaining > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please complete or skip all steps before finishing.'
            ]);
        }

        $now = date('Y-m-d H:i:s');

        $assignmentModel->update($assignmentId, [
            'status'       => 'completed',
            'completed_at' => $now,
            'updated_at'   => $now
        ]);

        $bookingModel->update($assign['booking_id'], [
            'status'     => 'completed',
            'updated_at' => $now
        ]);

        return $this->response->setJSON([
            'success'      => true,
            'message'      => 'Process finished',
            'completed_at' => $now
        ]);
    }

    public function doneJobStep()
    {
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $id = $this->request->getPost('job_step_id');
        if (!$id) return $this->response->setJSON(['success' => false, 'message' => 'Invalid step']);

        $jobStepsModel = new JobStationModel();
        $step = $jobStepsModel->find($id);
        if (!$step) return $this->response->setJSON(['success' => false, 'message' => 'Step not found']);

        $now = date('Y-m-d H:i:s');

        $jobStepsModel->update($id, [
            'status'     => 'done',
            'end_time'   => $now,     // optional
            'updated_at' => $now
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Step marked done']);
    }


    public function skipJobStep()
    {
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

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

    public function assignNext()
    {
        if (!session()->get('employee_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $bookingId   = $this->request->getPost('booking_id');
        $stationId   = $this->request->getPost('station_id');
        $employeeId  = $this->request->getPost('employee_id');
        $note        = $this->request->getPost('note');

        if (!$bookingId || !$stationId || !$employeeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required data']);
        }

        $assignmentModel = new BookingAssignmentModel();

        // prevent duplicate same station assignment for same booking
        $exists = $assignmentModel
            ->where('booking_id', $bookingId)
            ->where('station_id', $stationId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'This station is already assigned/active for this booking']);
        }

        $now = date('Y-m-d H:i:s');

        $newId = $assignmentModel->insert([
            'booking_id'   => $bookingId,
            'station_id'   => $stationId,
            'employee_id'  => $employeeId,
            'status'       => 'assigned',
            'notes'        => $note,
            'assigned_at'  => $now,
            'updated_at'   => $now,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Next station assigned',
            'id'      => $newId
        ]);
    }
}
