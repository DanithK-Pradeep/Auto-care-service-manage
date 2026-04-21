<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobStationStepsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'station_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'sequence_no' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'done', 'skipped'],
                'default' => 'pending',
            ],
            'assigned_employee_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('station_id', 'stations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_station_steps');
    }

    public function down()
    {
        $this->forge->dropTable('job_station_steps');
    }
}
