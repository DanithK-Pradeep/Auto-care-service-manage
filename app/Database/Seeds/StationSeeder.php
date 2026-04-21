<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 3,
                'station_type_id' => 1,
                'name' => 'Washing  A',
                'bay_no' => 1,
                'status' => 'active',
                'capacity' => 1,
            ],
            [
                'id' => 6,
                'station_type_id' => 2,
                'name' => 'Service A',
                'bay_no' => 1,
                'status' => 'active',
                'capacity' => 2,
            ],
            [
                'id' => 7,
                'station_type_id' => 3,
                'name' => 'Repair A',
                'bay_no' => 1,
                'status' => 'active',
                'capacity' => 2,
            ],
            [
                'id' => 8,
                'station_type_id' => 4,
                'name' => 'InspectionA',
                'bay_no' => 1,
                'status' => 'active',
                'capacity' => 1,
            ],
            [
                'id' => 9,
                'station_type_id' => 5,
                'name' => 'Handover A',
                'bay_no' => 1,
                'status' => 'active',
                'capacity' => 1,
            ],
        ];

        $this->db->table('stations')->insertBatch($data);
    }
}
