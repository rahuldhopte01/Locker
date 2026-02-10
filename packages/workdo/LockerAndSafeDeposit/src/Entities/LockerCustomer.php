<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'id_proof',
        'is_active',
        'workspace',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Full name for display and dropdowns (customers have no login access).
     */
    public function getNameAttribute(): string
    {
        $first = $this->attributes['first_name'] ?? '';
        $last  = $this->attributes['last_name'] ?? '';

        return trim($first . ' ' . $last) ?: '-';
    }

    public function bookings()
    {
        return $this->hasMany(LockerBooking::class, 'customer_id');
    }
}
