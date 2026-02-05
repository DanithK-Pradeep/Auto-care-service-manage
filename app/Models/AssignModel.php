<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignModel extends Model
{
    protected $table      = 'employee_station';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $allowedFields = [
        'employee_id',
        'station_id',
        'is_primary',
        'assigned_at',
        'updated_at',
    ];

    // Let CodeIgniter manage timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'assigned_at';
    protected $updatedField  = 'updated_at';
}
