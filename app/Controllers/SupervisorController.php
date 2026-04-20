<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class SupervisorController extends BaseController
{
    public function dashboard()
    {
        // 🛡️ Security Check: ලොග් වෙලා ඉන්නේ Supervisor ද?
        $role = strtolower(trim(session()->get('employee_role') ?? ''));
        
        if ($role !== 'supervisor') {
            return redirect()->to(site_url('employee/login'))->with('error', 'Access Denied.');
        }

        $db = \Config\Database::connect();
        $employeeId = session()->get('employee_id');
        
        // 1. Status එක 'completed' වෙලා තියෙන (Release කරන්න ලෑස්ති) Bookings ගන්නවා
        $readyBookings = $db->table('bookings b')
            ->select('b.*')
            ->join('booking_assignments ba', 'ba.booking_id = b.id')
            ->where('b.status', 'completed')
            ->where('ba.employee_id', $employeeId)
            ->where('ba.status', 'in_progress') // Approve කරලා තියෙන්නම ඕනේ!
            ->orderBy('b.completed_at', 'DESC')
            ->get()
            ->getResultArray();

        // 2. හැම Booking එකකටම අදාළව පාවිච්චි කරපු Spare Parts වල එකතුව හොයනවා
        foreach ($readyBookings as &$booking) {
            $sparePartsQuery = $db->table('spare_part_usages')
                ->selectSum('total_price')
                ->where('booking_id', $booking['id'])
                ->get()
                ->getRow();

            // Spare parts මුකුත් නැත්නම් 0.00 විදිහට සෙට් කරනවා
            $booking['total_spare_parts_cost'] = $sparePartsQuery->total_price ? (float)$sparePartsQuery->total_price : 0.00;
        }

        return view('employee/supervisor/dashboard', [
            'title'          => 'Vehicle Release & Billing',
            'activeMenu'     => 'supervisor_dashboard',
            'readyBookings'  => $readyBookings
        ]);
    }

    public function releaseVehicle()
    {
        // AJAX request එකක්ද කියලා බලනවා
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid Request']);
        }

        $role = strtolower(trim(session()->get('employee_role') ?? ''));

        if ($role !== 'supervisor') {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Access Denied. Supervisor only.'
            ]);
        }

        // Form එකෙන් එන දත්ත ලබා ගැනීම
        $bookingId      = (int) $this->request->getPost('booking_id');
        $serviceCharge  = (float) $this->request->getPost('service_charge');
        $sparePartsCost = (float) $this->request->getPost('spare_parts_cost');
        $discount       = (float) $this->request->getPost('discount');
        $paymentMethod  = $this->request->getPost('payment_method');

        // 🧮 Net Total එක Server පැත්තෙනුත් ගණනය කරනවා (ආරක්ෂාවට)
        $netTotal = ($serviceCharge + $sparePartsCost) - $discount;
        if ($netTotal < 0) $netTotal = 0;

        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->transStart();

        // ✨ ප්‍රධාන Bookings Table එක Update කිරීම (වාහනය Release කිරීම)
        $db->table('bookings')->where('id', $bookingId)->update([
            'status'           => 'released', // Status එක released වෙනවා
            'service_charge'   => $serviceCharge,
            'spare_parts_cost' => $sparePartsCost,
            'discount'         => $discount,
            'net_total'        => $netTotal,
            'payment_method'   => $paymentMethod,
            'released_at'      => $now,
            'updated_at'       => $now
        ]);

        // ✨ ප්‍රධාන Assignments Table එක Update කිරීම (වාහනය Completed කිරීම)
        $employeeId = session()->get('employee_id');

        $db->table('booking_assignments')
            ->where('booking_id', $bookingId)
            ->where('employee_id', $employeeId)
            ->where('status', 'in_progress')
            ->update([
                'status'       => 'released', // Assignment එක close කරනවා
                'completed_at' => $now,
                'updated_at'   => $now
            ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Payment Confirmed! Vehicle has been released successfully.'
        ]);
    }

    public function history()
    {
        $role = strtolower(trim(session()->get('employee_role') ?? ''));
        if ($role !== 'supervisor') {
            return redirect()->to(site_url('employee/dashboard'))->with('error', 'Access Denied.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('bookings');

        // Completed සහ Released වාහන පමණක් ගන්නවා
        $builder->whereIn('status', ['completed', 'released']);

        // 🔍 Filters අල්ලගැනීම (Search & Dates)
        $search = $this->request->getGet('search');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Search Box එකේ මොනවා හරි ටයිප් කරලා නම්
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('vehicle_model', $search)
                    ->orLike('id', $search) // Booking ID එකෙන් හොයන්න
                    ->groupEnd();
        }

        // Date Range එකක් දීලා නම්
        if (!empty($startDate)) {
            $builder->where('DATE(created_at) >=', $startDate);
        }
        if (!empty($endDate)) {
            $builder->where('DATE(created_at) <=', $endDate);
        }

        // අලුත්ම ඒවා උඩින්ම පෙන්වන්න
        $builder->orderBy('updated_at', 'DESC');
        $historyData = $builder->get()->getResultArray();

       $data = [
            'title'      => 'Service History & Reports',
            'activeMenu' => 'history', // ✨ මෙන්න මේක වෙනස් කරන්න
            'history'    => $historyData,
            'search'     => $search,
            'startDate'  => $startDate,
            'endDate'    => $endDate
        ];

        return view('employee/supervisor/history', $data);
    }
}