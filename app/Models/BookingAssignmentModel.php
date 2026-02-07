<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingAssignmentModel extends Model
{
    protected $table = 'booking_assignments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'booking_id',
        'station_id',
        'employee_id',
        'status',
        'notes',
        'assigned_at',
        'started_at',
        'completed_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'assigned_at';
    protected $updatedField  = 'updated_at';
}
