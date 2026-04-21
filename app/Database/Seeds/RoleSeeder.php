<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'role_name' => 'Technician'],
            ['id' => 2, 'role_name' => 'Washer'],
            ['id' => 3, 'role_name' => 'Painter'],
            ['id' => 4, 'role_name' => 'Inspector'],
            ['id' => 5, 'role_name' => 'Supervisor'],
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}
