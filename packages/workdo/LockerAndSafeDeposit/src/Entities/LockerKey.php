<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_id',
        'customer_id',
        'key_type',
        'issue_date',
        'status',
        'workspace',
        'created_by'
    ];

    public function locker()
    {
        return $this->hasOne(Locker::class,'id','locker_id');
    }

    public function customer()
    {
        return $this->hasOne(LockerCustomer::class,'id','customer_id');
    }

    public function booking()
    {
        return $this->hasOne(LockerBooking::class,'locker_id','locker_id');
    }
}
