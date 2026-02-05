<?php

namespace App\Models;

use CodeIgniter\Model;

class StationTypeModel extends Model
{
    protected $table = 'station_types';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'name',
        'code'
    ];
}
