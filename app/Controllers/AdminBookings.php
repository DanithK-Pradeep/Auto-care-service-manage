<?php

namespace App\Controllers;

use App\Models\BookingModel;

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

        $data = [
            'title' => 'Bookings',
            'activeMenu' => 'bookings',
            'bookings' => $bookingModel
                ->orderBy('id', 'DESC')
                ->findAll()
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
            return redirect()->to('/admin/login')
                ->with('error', 'Please login first');
        }

        $bookingModel = new \App\Models\BookingModel();
        $booking = $bookingModel->find($id);

        if (!$booking) {
            return redirect()->to('/admin/bookings')
                ->with('error', 'Booking not found');
        }

        $data = [
            'title' => 'Booking Details',
            'activeMenu' => 'bookings',
            'booking' => $booking
        ];

        return view('admin/bookings/view', $data);
    }

    
}
