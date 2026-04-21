<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SparePartCategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Engine Parts',
                'description' => 'Parts related to engine maintenance',
            ],
            [
                'id' => 2,
                'name' => 'Body Parts',
                'description' => 'External body parts and panels',
            ],
            [
                'id' => 3,
                'name' => 'Electrical',
                'description' => 'Electrical components',
            ],
        ];

        $this->db->table('spare_part_categories')->insertBatch($data);
    }
}
