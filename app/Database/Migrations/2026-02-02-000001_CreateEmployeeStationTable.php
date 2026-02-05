<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeeStationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'employee_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'station_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'assigned_at'  => ['type' => 'DATETIME'],
            'created_at'   => ['type' => 'DATETIME'],
            'updated_at'   => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('station_id', 'stations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employee_station');
    }

    public function down()
    {
        $this->forge->dropTable('employee_station', true);
    }
}
