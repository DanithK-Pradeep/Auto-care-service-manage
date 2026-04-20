<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $this->data['services'] = [
            [
                'title' => 'Oil Change',
                'slug' => 'oil-change',
                'desc'  => 'Keep your engine smooth and long-lasting with regular oil changes.',
                'long_description' => 'Our premium oil change service includes:<br>• Full synthetic or semi-synthetic oil replacement.<br>• High-quality oil filter change.<br>• Engine fluid levels check.<br>• Visual inspection of belts and hoses to prevent future breakdowns.',
                'image' => 'https://images.pexels.com/photos/13065690/pexels-photo-13065690.jpeg',
                'link'  => '/services/oil-change'
            ],
            [
                'title' => 'Car Wash',
                'slug' => 'car-wash',
                'desc'  => 'Professional exterior and interior cleaning for a fresh look.',
                'long_description' => 'Give your vehicle a brand-new shine with our detailed wash:<br>• High-pressure exterior foam wash.<br>• Complete interior vacuum cleaning and dashboard polishing.<br>• Under-carriage cleaning to remove mud and salt.<br>• Tire dressing and window glass cleaning.',
                'image' => 'https://washhounds.com/wp-content/uploads/35413014_m_normal_none.webp',
                'link'  => '/services/car-wash'
            ],
            [
                'title' => 'Engine Repair',
                'slug' => 'engine-repair',
                'desc'  => 'Expert engine diagnostics and repairs using modern tools.',
                'long_description' => 'We handle everything from minor fixes to major overhauls:<br>• Advanced computer-aided engine diagnostics.<br>• Troubleshooting "Check Engine" lights.<br>• Repairing oil leaks and coolant system issues.<br>• Improving engine performance and fuel efficiency.',
                'image' => 'https://www.shutterstock.com/image-photo/mechanic-repairing-car-engine-using-600nw-2622052919.jpg',
                'link'  => '/services/engine-repair'
            ],
            [
                'title' => 'Tire Change',
                'slug' => 'tire-change',
                'desc'  => 'Tire replacement and balancing for a smooth, safe ride.',
                'long_description' => 'Ensure a safe journey with professional tire care:<br>• Replacement of worn-out tires with top-tier brands.<br>• Precision wheel balancing and pressure check.<br>• Inspection for punctures or sidewall damage.<br>• Proper torque application for all wheel bolts.',
                'image' => 'https://img.freepik.com/free-photo/car-mechanic-changing-wheels-car_1303-26653.jpg?t=st=1768407062~exp=1768410662~hmac=dde3be2280bdc0fee42cd73f9f3cec7abfbabde9eddaf40acf5b8b85c2257554',
                'link'  => '/services/tire-change'
            ],
            [
                'title' => 'Battery Check',
                'slug' => 'battery-check',
                'desc'  => 'Ensure reliable starts with complete battery testing.',
                'long_description' => 'Avoid unexpected car failures with our battery service:<br>• Professional voltage and load testing.<br>• Cleaning battery terminals to prevent corrosion.<br>• Alternator performance check.<br>• Expert battery replacement if required.',
                'image' => 'https://www.shutterstock.com/shutterstock/photos/2388561023/display_1500/stock-photo-close-up-auto-mechanic-hands-with-working-gloves-checking-vehicle-battery-by-battery-tester-in-auto-2388561023.jpg',
                'link'  => '/services/battery-check'
            ],
            [
                'title' => 'Brake Service',
                'slug' => 'brake-service',
                'desc'  => 'Stay safe with professional brake inspection and service.',
                'long_description' => 'Safety is our top priority. Our brake service covers:<br>• Brake pad and shoe replacement.<br>• Rotor/drum resurfacing or replacement.<br>• Brake fluid flush and refill.<br>• Full inspection of the hydraulic braking system.',
                'image' => 'https://media.istockphoto.com/id/1193247902/photo/handsome-mechanic-in-uniform.jpg?s=2048x2048&w=is&k=20&c=pd3c6d7MxEbNJD-fMJ6hAXDKdP_QqLhgFOO48uZhqOI=',
                'link'  => '/services/brake-service'
            ],
        ];

        $this->data['whyChooseUs'] = [
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

        $this->data['counters'] = [
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
        



        $this->data['servicesList'] = [
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



        return view('index', $this->data);
    }

    // App/Controllers/Home.php ඇතුළත

    public function about()
    {
        // About පේජ් එකට පමණක් අදාළ දත්ත
        $this->data['team'] = [
            ['name' => 'Asanka Perera', 'role' => 'Master Mechanic', 'img' => 'https://i.pravatar.cc/300?img=11'],
            ['name' => 'Dilshan Silva', 'role' => 'Engine Specialist', 'img' => 'https://i.pravatar.cc/300?img=12'],
            ['name' => 'Kamal Gunaratne', 'role' => 'Service Manager', 'img' => 'https://i.pravatar.cc/300?img=13'],
        ];

        return view('about', $this->data);
    }

    // App/Controllers/Home.php

    public function contact()
    {
        // අමතර දත්ත අවශ්‍ය නැත, BaseController එකෙන් navItems ලැබෙනු ඇත
        return view('contact', $this->data);
    }
}
