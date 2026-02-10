<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'building',
        'floor',
        'section',
        'address',
        'workspace',
        'created_by',
    ];

    public function lockers()
    {
        return $this->hasMany(Locker::class, 'location_id');
    }
}
