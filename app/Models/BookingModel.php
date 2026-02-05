<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';

    protected $allowedFields = [

        'name',
        'phone',
        'service',
        'vehicle_model',
        'message',
        'booking_date',
        'status',
        'reject_reason'
        
        

        
        
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
