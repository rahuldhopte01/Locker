<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'locker_id',
        'customer_id',
        'start_date',
        'end_date',
        'duration',
        'amount',
        'payment_status',
        'last_payment_date',
        'next_payment_due',
        'reservation_date',
        'reservation_expires_at',
        'is_reservation',
        'reservation_status',
        'workspace',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_payment_date' => 'date',
        'next_payment_due' => 'date',
        'reservation_date' => 'datetime',
        'reservation_expires_at' => 'datetime',
        'is_reservation' => 'boolean',
    ];

    public static $paymentStatuses = [
        'pending'  => 'Pending',
        'partial'  => 'Partial',
        'paid'     => 'Paid',
        'overdue'  => 'Overdue',
    ];

    public static $reservationStatuses = [
        'pending'   => 'Pending',
        'confirmed' => 'Confirmed',
        'expired'   => 'Expired',
        'converted' => 'Converted',
    ];

    public function locker()
    {
        return $this->hasOne(Locker::class,'id','locker_id');
    }

    public function customer()
    {
        return $this->hasOne(LockerCustomer::class,'id','customer_id');
    }

    public function payments()
    {
        return $this->hasMany(LockerBookingPayment::class, 'booking_id', 'id');
    }

    public function getDue()
    {
        $due = 0;

        foreach ($this->payments as $payment) {
            $due += $payment->amount;
        }

        return ($this->amount - $due);
    }
}
