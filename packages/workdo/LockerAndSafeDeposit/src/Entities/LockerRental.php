<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_id',
        'customer_id',
        'start_date',
        'end_date',
        'payment_status',
        'last_payment_date',
        'next_payment_due',
        'payment_method',
        'payment_type',
        'monthly_amount',
        'workspace',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_payment_date' => 'date',
        'next_payment_due' => 'date',
        'monthly_amount' => 'decimal:2',
    ];

    /** payment_status: paid, unpaid, overdue, partial */
    public static $paymentStatuses = [
        'paid'    => 'Paid',
        'unpaid'  => 'Unpaid',
        'overdue' => 'Overdue',
        'partial' => 'Partial',
    ];

    /** payment_method: online, cash */
    public static $paymentMethods = [
        'online' => 'Online',
        'cash'   => 'Cash',
    ];

    /** payment_type: full, partial */
    public static $paymentTypes = [
        'full'    => 'Full',
        'partial' => 'Partial',
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

    public function customer()
    {
        return $this->belongsTo(LockerCustomer::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(LockerRentalPayment::class, 'rental_id');
    }

    public function historyEntries()
    {
        return $this->hasMany(LockerLockerHistory::class, 'rental_id');
    }

    /** Whether the rental is currently active (no end_date or end_date in future). */
    public function getIsOngoingAttribute(): bool
    {
        return $this->end_date === null || $this->end_date->isFuture();
    }
}
