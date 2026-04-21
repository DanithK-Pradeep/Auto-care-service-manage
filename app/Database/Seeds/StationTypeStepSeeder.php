<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StationTypeStepSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Washing Bay (station_type_id: 1)
            ['id' => 1, 'station_type_id' => 1, 'sequence_no' => 1, 'title' => 'Pre-check'],
            ['id' => 2, 'station_type_id' => 1, 'sequence_no' => 2, 'title' => 'Pre-rinse'],
            ['id' => 3, 'station_type_id' => 1, 'sequence_no' => 3, 'title' => 'Foam / Soap'],
            ['id' => 4, 'station_type_id' => 1, 'sequence_no' => 4, 'title' => 'Scrub'],
            ['id' => 5, 'station_type_id' => 1, 'sequence_no' => 5, 'title' => 'Final rinse'],
            ['id' => 6, 'station_type_id' => 1, 'sequence_no' => 6, 'title' => 'Drying'],
            ['id' => 7, 'station_type_id' => 1, 'sequence_no' => 7, 'title' => 'Final inspection'],

            // Service Bay (station_type_id: 2)
            ['id' => 8, 'station_type_id' => 2, 'sequence_no' => 1, 'title' => 'Vehicle receive & confirm job card'],
            ['id' => 9, 'station_type_id' => 2, 'sequence_no' => 2, 'title' => 'Record mileage / fuel level'],
            ['id' => 10, 'station_type_id' => 2, 'sequence_no' => 3, 'title' => 'Install seat & floor covers'],
            ['id' => 11, 'station_type_id' => 2, 'sequence_no' => 4, 'title' => 'Under-hood inspection (leaks/belts/hoses)'],
            ['id' => 12, 'station_type_id' => 2, 'sequence_no' => 5, 'title' => 'OBD scan (if needed)'],
            ['id' => 13, 'station_type_id' => 2, 'sequence_no' => 6, 'title' => 'Drain engine oil'],
            ['id' => 14, 'station_type_id' => 2, 'sequence_no' => 7, 'title' => 'Replace oil filter'],
            ['id' => 15, 'station_type_id' => 2, 'sequence_no' => 8, 'title' => 'Refill engine oil (correct grade)'],
            ['id' => 16, 'station_type_id' => 2, 'sequence_no' => 9, 'title' => 'Check/replace air filter'],
            ['id' => 17, 'station_type_id' => 2, 'sequence_no' => 10, 'title' => 'Check/replace cabin filter'],
            ['id' => 18, 'station_type_id' => 2, 'sequence_no' => 11, 'title' => 'Top-up fluids (coolant/brake/washer)'],
            ['id' => 19, 'station_type_id' => 2, 'sequence_no' => 12, 'title' => 'Tyre pressure + quick brake check'],
            ['id' => 20, 'station_type_id' => 2, 'sequence_no' => 13, 'title' => 'Start engine, check leaks & reset service light'],
            ['id' => 21, 'station_type_id' => 2, 'sequence_no' => 14, 'title' => 'Service QC + update service record'],

            // Repair Bay (station_type_id: 3)
            ['id' => 22, 'station_type_id' => 3, 'sequence_no' => 1, 'title' => 'Receive vehicle & confirm complaint'],
            ['id' => 23, 'station_type_id' => 3, 'sequence_no' => 2, 'title' => 'Road test (if required)'],
            ['id' => 24, 'station_type_id' => 3, 'sequence_no' => 3, 'title' => 'OBD scan / fault code reading'],
            ['id' => 25, 'station_type_id' => 3, 'sequence_no' => 4, 'title' => 'Visual inspection (engine bay/underbody)'],
            ['id' => 26, 'station_type_id' => 3, 'sequence_no' => 5, 'title' => 'Diagnose root cause'],
            ['id' => 27, 'station_type_id' => 3, 'sequence_no' => 6, 'title' => 'Prepare estimate / parts list'],
            ['id' => 28, 'station_type_id' => 3, 'sequence_no' => 7, 'title' => 'Get customer approval'],
            ['id' => 29, 'station_type_id' => 3, 'sequence_no' => 8, 'title' => 'Collect parts / tools'],
            ['id' => 30, 'station_type_id' => 3, 'sequence_no' => 9, 'title' => 'Disassembly / access repair area'],
            ['id' => 31, 'station_type_id' => 3, 'sequence_no' => 10, 'title' => 'Repair / replace components'],
            ['id' => 32, 'station_type_id' => 3, 'sequence_no' => 11, 'title' => 'Reassembly & torque checks'],
            ['id' => 33, 'station_type_id' => 3, 'sequence_no' => 12, 'title' => 'Fluid refill / bleeding (if needed)'],
            ['id' => 34, 'station_type_id' => 3, 'sequence_no' => 13, 'title' => 'Clear codes / calibration (if needed)'],
            ['id' => 35, 'station_type_id' => 3, 'sequence_no' => 14, 'title' => 'Functional testing (stationary)'],
            ['id' => 36, 'station_type_id' => 3, 'sequence_no' => 15, 'title' => 'Final road test'],
            ['id' => 37, 'station_type_id' => 3, 'sequence_no' => 16, 'title' => 'Final QC + update job notes'],

            // Inspection Bay (station_type_id: 4)
            ['id' => 38, 'station_type_id' => 4, 'sequence_no' => 1, 'title' => 'Verify all previous bay assignments are completed'],
            ['id' => 39, 'station_type_id' => 4, 'sequence_no' => 2, 'title' => 'Final under-hood check (fluid caps secured, no leaks)'],
            ['id' => 40, 'station_type_id' => 4, 'sequence_no' => 3, 'title' => 'Verify wheel lug nuts torque & tire pressure'],
            ['id' => 41, 'station_type_id' => 4, 'sequence_no' => 4, 'title' => 'Check all exterior lights & indicators'],
            ['id' => 42, 'station_type_id' => 4, 'sequence_no' => 5, 'title' => 'Interior cleanup (remove seat/floor covers, wipe steering)'],
            ['id' => 43, 'station_type_id' => 4, 'sequence_no' => 6, 'title' => 'Exterior walkaround & glass cleaning'],
            ['id' => 44, 'station_type_id' => 4, 'sequence_no' => 7, 'title' => 'Dashboard check (no warning lights, service reset confirmed)'],
            ['id' => 45, 'station_type_id' => 4, 'sequence_no' => 8, 'title' => 'Final Quality Control (QC) sign-off & ready for customer'],
        ];

        $this->db->table('station_type_steps')->insertBatch($data);
    }
}
