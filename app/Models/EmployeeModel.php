<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'role',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
}
