<?php

namespace App\Models;

use CodeIgniter\Model;

class SparePartCategoryModel extends Model
{
    protected $table      = 'spare_part_categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'name',
        'description',
    ];

    protected $useTimestamps = true; 
}
