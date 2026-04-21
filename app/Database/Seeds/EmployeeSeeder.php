<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'first_name' => 'Danith K',
                'last_name' => 'Pradeep',
                'phone' => '0767888425',
                'email' => 'pradeep@gmail.com',
                'hourly_rate' => 250.00,
                'password' => '$2y$10$.1GYeywOOp9X4DdQUTIaTe1IsjTY3drtBReHQu2ivJbwTJ8haCQvq',
                'role' => 'Technician',
                'status' => 'active',
                'created_at' => '2026-02-05 18:51:11',
                'updated_at' => '2026-02-05 18:51:11',
            ],
            [
                'id' => 2,
                'first_name' => 'james',
                'last_name' => 'silva',
                'phone' => '0764561232',
                'email' => 'silva@gmail.com',
                'hourly_rate' => 300.00,
                'password' => '$2y$10$fuH4xkWOszCDprlWcgyO1O84rDRtu59OLEQ3VrsaeWakDvfAFZQA6',
                'role' => 'Washer',
                'status' => 'active',
                'created_at' => '2026-02-05 18:51:48',
                'updated_at' => '2026-02-05 18:51:48',
            ],
            [
                'id' => 3,
                'first_name' => 'wanidu ',
                'last_name' => 'hasaranga',
                'phone' => '0784563214',
                'email' => 'waniduhasaranga@gmail.com',
                'hourly_rate' => 400.00,
                'password' => '$2y$10$KAhQ645G/B41NWay/roh0uZt/wYzsxyz2XsEcpUTKs0WN3bleTDp6',
                'role' => 'Painter',
                'status' => 'active',
                'created_at' => '2026-02-05 19:30:40',
                'updated_at' => '2026-02-05 19:30:40',
            ],
            [
                'id' => 4,
                'first_name' => 'lahiru',
                'last_name' => 'kumara',
                'phone' => '0755287896',
                'email' => 'lahiru@gmail.com',
                'hourly_rate' => 0.00,
                'password' => '$2y$10$Ni1xvIL0cUmDgyFjvg0qzusdZY8rXRSlmo7ODvET33.p4U6otJImm',
                'role' => 'Washer',
                'status' => 'active',
                'created_at' => '2026-02-08 18:38:13',
                'updated_at' => '2026-02-08 18:38:13',
            ],
            [
                'id' => 5,
                'first_name' => 'rane',
                'last_name' => 'kumara',
                'phone' => '0112567894',
                'email' => 'rane@gmail.com',
                'hourly_rate' => 0.00,
                'password' => '$2y$10$/cy.wO92LaQfrpRHAQbzueveVFMIFBQ4Opg0WKx9pPQjDKm4NXeL.',
                'role' => 'Inspector ',
                'status' => 'active',
                'created_at' => '2026-03-17 22:32:59',
                'updated_at' => '2026-03-17 22:32:59',
            ],
            [
                'id' => 6,
                'first_name' => 'yassho',
                'last_name' => 'kumara',
                'phone' => '0784568521',
                'email' => 'yasho@gmail.com',
                'hourly_rate' => 0.00,
                'password' => '$2y$10$4b4lxkSZzUtm9Z42uMaCAOiziUsMGjRf3WUewIBKN4LgkpKckF8TS',
                'role' => 'Supervisor ',
                'status' => 'active',
                'created_at' => '2026-03-18 17:07:19',
                'updated_at' => '2026-03-18 17:07:19',
            ],
        ];

        $this->db->table('employees')->insertBatch($data);
    }
}
