<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locker extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_number',
        'location_id',
        'size',
        'status',
        'monthly_rate',
        'is_available',
        'workspace',
        'created_by',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'monthly_rate' => 'decimal:2',
    ];

    /** Size enum: small, medium, large, extra_large */
    public static $sizes = [
        'small'       => 'Small',
        'medium'      => 'Medium',
        'large'       => 'Large',
        'extra_large' => 'Extra Large',
    ];

    /** Status enum: active, inactive, reserved, maintenance */
    public static $status = [
        'active'      => 'Active',
        'inactive'     => 'Inactive',
        'reserved'     => 'Reserved',
        'maintenance'  => 'Maintenance',
    ];

    public function location()
    {
        return $this->belongsTo(LockerLocation::class, 'location_id');
    }

    public function bookings()
    {
        return $this->hasMany(LockerBooking::class, 'locker_id');
    }

    /** Monthly rate in EUR (spec). For yearly use monthly_rate * 12. */
    public function getYearlyRateAttribute()
    {
        return (float) $this->monthly_rate * 12;
    }
}
