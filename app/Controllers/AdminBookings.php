<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\EmployeeModel;
use App\Models\StationModel;
use App\Models\BookingAssignmentModel;

class AdminBookings extends BaseController


{

    public function index()
    {
        // Session protection (NO filter, manual check)
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')
                ->with('error', 'Please login first');
        }
        $bookingModel = new BookingModel();
        $employeeModel = new EmployeeModel();
        $stationModel = new StationModel();

        $data = [

            // Pending bookings list
            'bookings' => $bookingModel

                ->orderBy('id', 'DESC')
                ->findAll(),

            // Only stations that can accept work
            'stations' => $stationModel
                ->where('status', 'active')
                ->findAll(),

            // Only active employees
            'employees' => $employeeModel
                ->where('status', 'active')
                ->findAll(),
        ];


        return view('/admin/bookings/index', $data);
    }


    public function rejectBooking()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $id = $this->request->getPost('booking_id');
        $reason = $this->request->getPost('reject_reason');



        if (!$id || !$reason) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $bookingModel = new \App\Models\BookingModel();
        $booking = $bookingModel->find($id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Action not allowed');
        }


        $bookingModel->update($id, [
            'status' => 'rejected',
            'reject_reason' => $reason
        ]);

        return redirect()->back()->with('success', 'Booking rejected successfully');
    }

    public function view($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $bookingModel  = new BookingModel();
        $stationModel  = new StationModel();
        $employeeModel = new EmployeeModel();

        $booking = $bookingModel->find($id);
        if (!$booking) {
            return redirect()->to(site_url('admin/bookings'))->with('error', 'Booking not found');
        }

        return view('admin/bookings/view', [
            'booking'   => $booking,
            'stations'  => $stationModel->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'employees' => $employeeModel->where('status', 'active')->orderBy('first_name', 'ASC')->findAll(),
        ]);
    }



    public function approve()
    {
        // 1) Protect route
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        // 2) Read POST values
        $bookingId  = $this->request->getPost('booking_id');
        $stationId  = $this->request->getPost('station_id');
        $employeeId = $this->request->getPost('employee_id');
        $notes      = $this->request->getPost('notes');

        // 3) Basic validation
        if (!$bookingId || !$stationId || !$employeeId) {
            $resp = ['success' => false, 'message' => 'Missing booking/station/employee'];
            return redirect()->to(site_url('admin/bookings'))->with('error', $resp['message']);
        }

        // 4) Load models
        $bookingModel  = new BookingModel();
        $stationModel  = new StationModel();
        $employeeModel = new EmployeeModel();
        $bookingAssignModel = new BookingAssignmentModel();

        // 5) Check booking exists and is pending
        $booking = $bookingModel->find($bookingId);
        if (!$booking) {
            $resp = ['success' => false, 'message' => 'Booking not found'];
            return $this->request->isAJAX()
                ? $this->response->setJSON($resp)
                : redirect()->to(site_url('admin/bookings'))->with('error', $resp['message']);
        }

        if ($booking['status'] !== 'pending') {
            $resp = ['success' => false, 'message' => 'Booking is not pending'];
            return $this->request->isAJAX()
                ? $this->response->setJSON($resp)
                : redirect()->to(site_url('admin/bookings'))->with('error', $resp['message']);
        }

        // 6) Check station valid
        $station = $stationModel->find($stationId);
        if (!$station || $station['status'] !== 'active') {
            $resp = ['success' => false, 'message' => 'Station not available'];
            return $this->request->isAJAX()
                ? $this->response->setJSON($resp)
                : redirect()->to(site_url('admin/bookings'))->with('error', $resp['message']);
        }

        // 7) Check employee valid
        $employee = $employeeModel->find($employeeId);
        if (!$employee || $employee['status'] !== 'active') {
            $resp = ['success' => false, 'message' => 'Employee not available'];
            return $this->request->isAJAX()
                ? $this->response->setJSON($resp)
                : redirect()->to(site_url('admin/bookings'))->with('error', $resp['message']);
        }

        // 8) Do the approve + assign (best inside transaction)
        $db = \Config\Database::connect();
        $db->transStart();

        // 8a) Update booking status
        $bookingModel->update($bookingId, [
            'status' => 'approved',
            'notes'  => $notes, // optional if you store notes in booking
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 8b) Insert booking assignment
        $bookingAssignModel->insert([
            'booking_id'  => $bookingId,
            'employee_id' => $employeeId,
            'station_id'  => $stationId,
            'status'      => 'assigned',
            'notes'       => $notes,



        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            $resp = ['success' => false, 'message' => 'Failed to approve & assign'];
            return $this->request->isAJAX()
                ? $this->response->setJSON($resp)
                : redirect()->to(site_url('admin/bookings'))->with('error', $resp['message']);
        }

        // 9) Response
        $resp = [
            'success' => true,
            'message' => 'Booking approved and assigned successfully',
        ];

        return $this->request->isAJAX()
            ? $this->response->setJSON($resp)
            : redirect()->to(site_url('/admin/bookings'))->with('success', $resp['message']);
    }
}
