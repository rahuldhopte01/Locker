<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerBookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'date',
        'amount',
        'description',
        'receipt',
        'workspace',
        'created_by'
    ];
}
