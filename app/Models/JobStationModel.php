<?php

use CodeIgniter\Model;

class AssignModel extends Model
{
    protected $table = 'job_station_steps';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = 
    [
        'booking_id',
        'station_id',
        'sequence_no',
        'status',
        'assingned_employee_id',
        'start_time',
        'end_time',
        'notes'

    ];

        protected $useTimestamps = true;
        

}