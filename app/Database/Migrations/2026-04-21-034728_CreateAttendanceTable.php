<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttendanceTable extends Migration
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
            'employee_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'work_date' => [
                'type' => 'DATE',
            ],
            'check_in' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'check_out' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'worked_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => '0.00',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['present', 'absent', 'leave'],
                'default' => 'present',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['employee_id', 'work_date'], 'unique_emp_date');
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('attendance');
    }

    public function down()
    {
        $this->forge->dropTable('attendance');
    }
}
