<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JobStationStepsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'booking_id' => 1,
                'station_id' => 3,
                'sequence_no' => 1,
                'status' => 'done',
                'assigned_employee_id' => 1,
                'end_time' => '2026-04-01 02:53:05',
                'updated_at' => '2026-04-01 02:53:05',
                'created_at' => '2026-04-01 02:52:55',
            ],
            [
                'id' => 2,
                'booking_id' => 1,
                'station_id' => 3,
                'sequence_no' => 2,
                'status' => 'done',
                'assigned_employee_id' => 1,
                'end_time' => '2026-04-01 02:53:07',
                'updated_at' => '2026-04-01 02:53:07',
                'created_at' => '2026-04-01 02:52:55',
            ],
            [
                'id' => 3,
                'booking_id' => 1,
                'station_id' => 3,
                'sequence_no' => 3,
                'status' => 'done',
                'assigned_employee_id' => 1,
                'end_time' => '2026-04-01 02:53:09',
                'updated_at' => '2026-04-01 02:53:09',
                'created_at' => '2026-04-01 02:52:55',
            ],
            [
                'id' => 4,
                'booking_id' => 1,
                'station_id' => 3,
                'sequence_no' => 4,
                'status' => 'done',
                'assigned_employee_id' => 1,
                'end_time' => '2026-04-01 02:53:10',
                'updated_at' => '2026-04-01 02:53:10',
                'created_at' => '2026-04-01 02:52:55',
            ],
            [
                'id' => 5,
                'booking_id' => 1,
                'station_id' => 3,
                'sequence_no' => 5,
                'status' => 'done',
                'assigned_employee_id' => 1,
                'end_time' => '2026-04-01 02:53:12',
                'updated_at' => '2026-04-01 02:53:12',
                'created_at' => '2026-04-01 02:52:55',
            ],
        ];

        $this->db->table('job_station_steps')->insertBatch($data);
    }
}
