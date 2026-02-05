<?php

namespace App\Models;

use CodeIgniter\Model;

class StationModel extends Model
{
    protected $table = 'stations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        
        'station_type_id',
        'name',
        'bay_no',
        'status',
        'capacity'
    ];
}
