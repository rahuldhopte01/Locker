<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_id',
        'customer_id',
        'start_date',
        'membership_type',
        'duration',
        'membership_fee',
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

}
