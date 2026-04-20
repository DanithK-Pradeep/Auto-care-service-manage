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
        'admin_note',
        'reject_reason',
        
        // --- Inspector 
        'completed_at',
        'final_notes',
        'completed_by',

        
        // --- Supervisor 
        'service_charge',
        'spare_parts_cost',
        'discount',
        'net_total',
        'payment_method',
        'released_at'
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';
}