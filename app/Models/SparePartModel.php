<?php

namespace App\Models;

use CodeIgniter\Model;

class SparePartModel extends Model
{
    protected $table      = 'spare_parts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'category_id',
        'name',
        'sku',
        'stock_qty',
        'price',
        'description',
    ];

    protected $useTimestamps = true; // table has created_at, updated_at
}
