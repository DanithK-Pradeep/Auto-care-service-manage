<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StationTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Washing Bay', 'code' => 'WASH'],
            ['id' => 2, 'name' => 'Service Bay', 'code' => 'SERV'],
            ['id' => 3, 'name' => 'Repair Bay', 'code' => 'REPAIR'],
            ['id' => 4, 'name' => 'Inspection Bay', 'code' => 'INSP'],
            ['id' => 5, 'name' => 'Handover Bay', 'code' => 'HAND'],
        ];

        $this->db->table('station_types')->insertBatch($data);
    }
}
