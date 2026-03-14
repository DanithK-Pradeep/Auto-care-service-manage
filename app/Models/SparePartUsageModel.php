<?php

namespace App\Models;

use CodeIgniter\Model;

class SparePartUsageModel extends Model
{
    protected $table      = 'spare_part_usages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'booking_id',
        'spare_part_id',
        'station_id',
        'employee_id',
        'qty',
        'unit_price',
        'total_price',
    ];

    protected $useTimestamps = true; 
}
