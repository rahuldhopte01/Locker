<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locker extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_number',
        'locker_type',
        'locker_size',
        'max_capacity',
        'price_of_month',
        'price_of_year',
        'status',
        'location_id',
        'is_available',
        'workspace',
        'created_by'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public static $status = [
        'Available'   => 'Available',
        'UnAvailable' => 'UnAvailable',
    ];

    public static $sizes = [
        'small'  => 'Small',
        'medium' => 'Medium',
        'large'  => 'Large',
    ];

    public function location()
    {
        return $this->belongsTo(LockerLocation::class, 'location_id');
    }
    public function bookings()
    {
        return $this->hasMany(LockerBooking::class, 'locker_id');
    }
}
