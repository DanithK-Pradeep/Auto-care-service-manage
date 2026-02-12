<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\AssignModel;
use App\Models\BookingAssignmentModel;
use App\Models\BookingModel;

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
}
