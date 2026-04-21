<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SparePartUsagesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'booking_id' => 1,
                'spare_part_id' => 3,
                'station_id' => 3,
                'employee_id' => 1,
                'qty' => 1,
                'unit_price' => 120.00,
                'total_price' => 120.00,
                'note' => null,
                'created_at' => '2026-04-01 02:53:23',
                'updated_at' => '2026-04-01 02:53:23',
            ],
            [
                'id' => 2,
                'booking_id' => 1,
                'spare_part_id' => 4,
                'station_id' => 3,
                'employee_id' => 1,
                'qty' => 1,
                'unit_price' => 85.00,
                'total_price' => 85.00,
                'note' => null,
                'created_at' => '2026-04-01 02:53:32',
                'updated_at' => '2026-04-01 02:53:32',
            ],
            [
                'id' => 3,
                'booking_id' => 2,
                'spare_part_id' => 3,
                'station_id' => 3,
                'employee_id' => 1,
                'qty' => 1,
                'unit_price' => 120.00,
                'total_price' => 120.00,
                'note' => null,
                'created_at' => '2026-04-10 21:51:27',
                'updated_at' => '2026-04-10 21:51:27',
            ],
        ];

        $this->db->table('spare_part_usages')->insertBatch($data);
    }
}
