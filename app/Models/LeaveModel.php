<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveModel extends Model
{
    protected $table            = 'leave_requests';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['employee_id', 'leave_date', 'leave_type', 'reason', 'status', 'admin_note'];
    protected $useTimestamps    = true;
}