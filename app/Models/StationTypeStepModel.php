<?php

namespace App\Models;

use CodeIgniter\Model;

class StationTypeStepModel extends Model
{
    protected $table = 'station_type_steps';
    protected $primaryKey = 'id';
    protected $allowedFields = ['station_type_id', 'sequence_no', 'title'];
    protected $useTimestamps = true;
}
