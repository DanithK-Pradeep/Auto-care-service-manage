<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $services = [
            [
                'title' => 'Oil Change',
                'slug' => 'oil-change',
                'desc'  => 'Keep your engine smooth and long-lasting with regular oil changes.',
                'image' => 'https://images.pexels.com/photos/13065690/pexels-photo-13065690.jpeg',
                'link'  => '/services/oil-change'
            ],
            [
                'title' => 'Car Wash',
                'slug' => 'car-wash',
                'desc'  => 'Professional exterior and interior cleaning for a fresh look.',
                'image' => 'https://washhounds.com/wp-content/uploads/35413014_m_normal_none.webp',
                'link'  => '/services/car-wash'
            ],
            [
                'title' => 'Engine Repair',
                'slug' => 'engine-repair',
                'desc'  => 'Expert engine diagnostics and repairs using modern tools.',
                'image' => 'https://ultimatemechanics.co.nz/wp-content/uploads/2025/07/European-Car-Service-Auckland.jpg',
                'link'  => '/services/engine-repair'
            ],
            [
                'title' => 'Tire Change',
                'slug' => 'tire-change',
                'desc'  => 'Tire replacement and balancing for a smooth, safe ride.',
                'image' => 'https://img.freepik.com/free-photo/car-mechanic-changing-wheels-car_1303-26653.jpg?t=st=1768407062~exp=1768410662~hmac=dde3be2280bdc0fee42cd73f9f3cec7abfbabde9eddaf40acf5b8b85c2257554',
                'link'  => '/services/tire-change'
            ],
            [
                'title' => 'Battery Check',
                'slug' => 'battery-check',
                'desc'  => 'Ensure reliable starts with complete battery testing.',
                'image' => 'https://www.shutterstock.com/shutterstock/photos/2388561023/display_1500/stock-photo-close-up-auto-mechanic-hands-with-working-gloves-checking-vehicle-battery-by-battery-tester-in-auto-2388561023.jpg',
                'link'  => '/services/battery-check'
            ],
            [
                'title' => 'Brake Service',
                'slug' => 'brake-service',
                'desc'  => 'Stay safe with professional brake inspection and service.',
                'image' => 'https://media.istockphoto.com/id/1193247902/photo/handsome-mechanic-in-uniform.jpg?s=2048x2048&w=is&k=20&c=pd3c6d7MxEbNJD-fMJ6hAXDKdP_QqLhgFOO48uZhqOI=',
                'link'  => '/services/brake-service'
            ],
        ];

        $whyChooseUs = [
            [
                'icon' => '🛠️',
                'title' => 'Expert Technicians',
                'desc'  => 'Skilled and certified mechanics for all vehicle types.',
            ],
            [
                'icon' => '⏱️',
                'title' => 'Quick Service',
                'desc'  => 'Fast and efficient service without compromising quality.',
            ],
            [
                'icon' => '💰',
                'title' => 'Affordable Pricing',
                'desc'  => 'Transparent pricing with no hidden charges.',
            ],
            [
                'icon' => '⭐',
                'title' => 'Trusted Service',
                'desc'  => 'Trusted by hundreds of happy customers.',
            ],
        ];

        $counters = [
            [
                'value' => 10,
                'label' => 'Years Experience',
                'suffix' => '+',
            ],
            [
                'value' => 500,
                'label' => 'Happy Customers',
                'suffix' => '+',
            ],
            [
                'value' => 1200,
                'label' => 'Cars Serviced',
                'suffix' => '+',
            ],
            [
                'value' => 24,
                'label' => 'Support Available',
                'suffix' => '/7',
            ],
        ];
        $navItems = [
            [
                'label' => 'Home',
                'url'   => site_url('/'),
                'match' => '',
                'button' => false,
            ],
            [

                'label' => 'Services',
                'url'   => site_url('services'),
                'match' => 'services',
                'button' => false,

                'dropdown' => [
                    [
                        'label' => 'Oil Change',
                        'url'   => site_url('services/oil-change'),
                    ],
                    [
                        'label' => 'Car Wash',
                        'url'   => site_url('services/car-wash'),
                    ],
                    [
                        'label' => 'Engine Repair',
                        'url'   => site_url('services/engine-repair'),
                    ],
                    [
                        'label' => 'Brake Service',
                        'url'   => site_url('services/brake-service'),
                    ],

                ],


            ],
            [
                'label' => 'About',
                'url'   => '#about',
                'match' => 'about',
                'button' => false,
            ],
            [
                'label' => 'Contact',
                'url'   => '#contact',
                'match' => 'contact',
                'button' => false,
            ],
            [
                'label' => 'Book Service',
                'url'   => '#book',
                'match' => 'book',
                'button' => true,
            ],
            [
                'label' => '',
                'url'   => site_url('/admin/login'),
                'match'   => 'login',
                'button' => false,
            ],

            

            

        ];



        $servicesList = [
            'none' => 'Select a Service',
            'oil-change' => 'Oil Change',
            'car-wash' => 'Car Wash',
            'engine-repair' => 'Engine Repair',
            'tire-change' => 'Tire Change',
            'battery-check' => 'Battery Check',
            'brake-service' => 'Brake Service',
            'body-wash' => 'Body Wash',
            'paint-job' => 'Paint Job',
            'exhaust-repair' => 'Exhaust Repair',
            'wheel-alignment' => 'Wheel Alignment',
            'ac-repair' => 'AC Repair',
            'clutch-repair' => 'Clutch Repair',
            'suspension-repair' => 'Suspension Repair',
            'transmission-repair' => 'Transmission Repair',
            'wheel-balancing' => 'Wheel Balancing',
            'windshield-repair' => 'Windshield Repair',
            'headlight-restoration' => 'Headlight Restoration',
            'timing-belt-replacement' => 'Timing Belt Replacement',
            'radiator-repair' => 'Radiator Repair',
            'vehicle-inspection' => 'Vehicle Inspection',
            'air-filter-replacement' => 'Air Filter Replacement',
            'fuel-system-cleaning' => 'Fuel System Cleaning',
            'battery-replacement' => 'Battery Replacement',
            'spark-plug-replacement' => 'Spark Plug Replacement',
            'car-detailing' => 'Car Detailing',
            'upholstery-cleaning' => 'Upholstery Cleaning',
            'windshield-wiper-replacement' => 'Windshield Wiper Replacement',
            'flat-tire-repair' => 'Flat Tire Repair',
            'shocks-struts-replacement' => 'Shocks & Struts Replacement',
            'full-service' => 'Full Service',
            'special-service' => 'Special Service',
        ];



        $currentUrl = current_url();

        return view('index', ['services' => $services, 'whyChooseUs' => $whyChooseUs, 'counters' => $counters, 'navItems' => $navItems, 'currentUrl' => $currentUrl, 'servicesList' => $servicesList]);
    }
}
