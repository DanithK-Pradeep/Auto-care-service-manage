<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingAssignmentsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'booking_id' => 1,
                'station_id' => 3,
                'employee_id' => 1,
                'status' => 'handed_over',
                'notes' => 'hodpn',
                'assigned_at' => '2026-04-01 02:45:07',
                'started_at' => '2026-04-01 02:52:55',
                'completed_at' => '2026-04-01 02:54:05',
                'updated_at' => '2026-04-01 02:54:05',
            ],
            [
                'id' => 2,
                'booking_id' => 1,
                'station_id' => 6,
                'employee_id' => 3,
                'status' => 'handed_over',
                'notes' => 'wde hri bn',
                'assigned_at' => '2026-04-01 02:54:05',
                'started_at' => '2026-04-02 02:16:51',
                'completed_at' => '2026-04-02 02:17:56',
                'updated_at' => '2026-04-02 02:17:56',
            ],
            [
                'id' => 3,
                'booking_id' => 1,
                'station_id' => 9,
                'employee_id' => 6,
                'status' => 'completed',
                'notes' => 'iwri bro',
                'assigned_at' => '2026-04-02 02:17:56',
                'started_at' => '2026-04-02 02:28:12',
                'completed_at' => '2026-04-02 02:28:43',
                'updated_at' => '2026-04-02 02:28:43',
            ],
            [
                'id' => 4,
                'booking_id' => 2,
                'station_id' => 3,
                'employee_id' => 1,
                'status' => 'handed_over',
                'notes' => 'meka hdann',
                'assigned_at' => '2026-04-10 21:01:03',
                'started_at' => '2026-04-10 21:49:59',
                'completed_at' => '2026-04-10 21:52:19',
                'updated_at' => '2026-04-10 21:52:19',
            ],
            [
                'id' => 5,
                'booking_id' => 2,
                'station_id' => 8,
                'employee_id' => 5,
                'status' => 'handed_over',
                'notes' => '',
                'assigned_at' => '2026-04-10 21:52:19',
                'started_at' => '2026-04-11 07:21:23',
                'completed_at' => '2026-04-11 07:29:42',
                'updated_at' => '2026-04-11 07:29:42',
            ],
            [
                'id' => 6,
                'booking_id' => 3,
                'station_id' => 3,
                'employee_id' => 1,
                'status' => 'handed_over',
                'notes' => 'waniya wde hrinbn',
                'assigned_at' => '2026-04-11 05:47:53',
                'started_at' => '2026-04-11 06:09:20',
                'completed_at' => '2026-04-11 06:10:42',
                'updated_at' => '2026-04-11 06:10:42',
            ],
        ];

        $this->db->table('booking_assignments')->insertBatch($data);
    }
}
