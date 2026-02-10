<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_id',
        'technician_name',
        'repair_status',
        'reported_date',
        'repair_date',
        'description',
        'workspace',
        'created_by'
    ];

    public static $status = [
        'Pending'     => 'Pending',
        'In Progress' => 'In Progress',
        'Completed'   => 'Completed',
    ];

    public function locker()
    {
        return $this->hasOne(Locker::class,'id','locker_id');
    }
}
