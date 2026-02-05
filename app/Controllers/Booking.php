<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Booking extends BaseController
{

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'phone' => 'required|numeric|min_length[10]',
            'service' => 'required',
            'vehicle_model' => 'required',
            'booking_date' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
                'csrf'    => csrf_hash(),
            ]);
        }

        $bookingModel = new \App\Models\BookingModel();

        $bookingModel->insert([
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'service' => $this->request->getPost('service'),
            'vehicle_model' => $this->request->getPost('vehicle_model'),
            'message' => $this->request->getPost('message'),
            'booking_date' => $this->request->getPost('booking_date'),
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Your service has been booked successfully!',
            'csrf'    => csrf_hash(),
        ]);
    }
}
