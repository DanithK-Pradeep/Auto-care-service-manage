<?php

namespace App\Controllers;

class Services extends BaseController
{
    public function detail($slug)
    {
        // All service data
        $services = [
            'oil-change' => [
                'title' => 'Oil Change',
                'desc'  => 'Regular oil changes keep your engine healthy and improve performance.',
                'img'   => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738',
            ],
            'car-wash' => [
                'title' => 'Car Wash',
                'desc'  => 'Complete exterior and interior cleaning to keep your car fresh.',
                'img'   => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2',
            ],
            'engine-repair' => [
                'title' => 'Engine Repair',
                'desc'  => 'Professional engine diagnostics and repair services.',
                'img'   => 'https://images.unsplash.com/photo-1581090700227-1e37b190418e',
            ],
            'tire-change' => [
                'title' => 'Tire Change',
                'desc'  => 'Tire replacement and balancing for safe driving.',
                'img'   => 'https://images.unsplash.com/photo-1600369671236-e74521d4b10f',
            ],
            'battery-check' => [
                'title' => 'Battery Check',
                'desc'  => 'Battery testing to ensure reliable starts.',
                'img'   => 'https://images.unsplash.com/photo-1617886903355-9354f07b0b1e',
            ],
            'brake-service' => [
                'title' => 'Brake Service',
                'desc'  => 'Brake inspection and repair to keep you safe.',
                'img'   => 'https://images.unsplash.com/photo-1625047509168-a7026f36de04',
            ],
        ];

        // If service not found → 404 page
        if (!isset($services[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Service not found');
        }

        return view('service_detail', [
            'service' => $services[$slug]
        ]);
    }
}
