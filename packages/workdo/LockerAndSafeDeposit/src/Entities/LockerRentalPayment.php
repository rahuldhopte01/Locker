<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerRentalPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'payment_date',
        'amount',
        'payment_method',
        'payment_type',
        'receipt',
        'notes',
        'workspace',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
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

    public function rental()
    {
        return $this->belongsTo(LockerRental::class, 'rental_id');
    }
}
