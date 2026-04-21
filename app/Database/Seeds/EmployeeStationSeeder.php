<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeeStationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 13,
                'employee_id' => 1,
                'station_id' => 3,
                'is_primary' => 0,
                'assigned_at' => '2026-03-04 18:19:12',
                'updated_at' => '2026-03-04 18:19:12',
            ],
            [
                'id' => 14,
                'employee_id' => 3,
                'station_id' => 6,
                'is_primary' => 0,
                'assigned_at' => '2026-03-04 18:19:24',
                'updated_at' => '2026-03-04 18:19:24',
            ],
            [
                'id' => 15,
                'employee_id' => 2,
                'station_id' => 7,
                'is_primary' => 0,
                'assigned_at' => '2026-03-04 18:19:34',
                'updated_at' => '2026-03-04 18:19:34',
            ],
            [
                'id' => 16,
                'employee_id' => 5,
                'station_id' => 8,
                'is_primary' => 0,
                'assigned_at' => '2026-03-17 22:34:38',
                'updated_at' => '2026-03-17 22:34:38',
            ],
        ];

        $this->db->table('employee_station')->insertBatch($data);
    }
}
