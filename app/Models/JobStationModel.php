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
        'end_time',
        'updated_at',
        'created_at'
    ];


    protected $useTimestamps = false;
}
