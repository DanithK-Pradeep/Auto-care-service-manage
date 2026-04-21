<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SparePartSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Air Filter',
                'sku' => 'AF-101',
                'stock_qty' => 47,
                'price' => 15.00,
                'description' => null,
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Oil Filter',
                'sku' => 'OF-102',
                'stock_qty' => 99,
                'price' => 8.50,
                'description' => null,
            ],
            [
                'id' => 3,
                'category_id' => 2,
                'name' => 'Front Bumper',
                'sku' => 'FB-201',
                'stock_qty' => 2,
                'price' => 120.00,
                'description' => null,
            ],
            [
                'id' => 4,
                'category_id' => 3,
                'name' => 'Battery',
                'sku' => 'BAT-301',
                'stock_qty' => 15,
                'price' => 85.00,
                'description' => null,
            ],
        ];

        $this->db->table('spare_parts')->insertBatch($data);
    }
}
