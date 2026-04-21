<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'id' => 1,
            'username' => 'admin',
            'password' => '$2y$10$i3yKC.Pg0EyuKRBOQuwBp.a2rXjkJJ3n91k2igOoirEWX2q0BVc76',
        ];

        $this->db->table('admins')->insert($data);
    }
}
