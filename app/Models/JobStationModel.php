<?php

namespace App\Models;

use CodeIgniter\Model;



class JobStationModel extends Model
{
    protected $table = 'job_station_steps';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'booking_id',
        'station_id',
        'sequence_no',
        'status',
        'assigned_employee_id',
        'start_time',
        'end_time',
        'notes',
        'updated_at',
    ];


    protected $useTimestamps = false;
}
