<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStationTypeStepsTable extends Migration
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
            'station_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'sequence_no' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('station_type_id', 'station_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('station_type_steps');
    }

    public function down()
    {
        $this->forge->dropTable('station_type_steps');
    }
}
