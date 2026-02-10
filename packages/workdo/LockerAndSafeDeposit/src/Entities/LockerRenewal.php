<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'renewal_date',
        'status',
        'workspace',
        'created_by'
    ];

    public function customer()
    {
        return $this->hasOne(LockerCustomer::class,'id','customer_id');
    }

    public function booking()
    {
        return $this->hasOne(LockerBooking::class,'id','booking_id');
    }
}
