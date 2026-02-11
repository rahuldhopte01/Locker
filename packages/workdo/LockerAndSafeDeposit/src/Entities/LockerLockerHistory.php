<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerLockerHistory extends Model
{
    use HasFactory;

    protected $table = 'locker_locker_history';

    public $timestamps = false; // we use occurred_at only

    protected $fillable = [
        'locker_id',
        'rental_id',
        'event_type',
        'description',
        'related_type',
        'related_id',
        'metadata',
        'workspace',
        'created_by',
        'occurred_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    /** Event types for the locker timeline. */
    public static $eventTypes = [
        'rental_started'           => 'Rental started',
        'rental_ended'              => 'Rental ended',
        'payment_received'          => 'Payment received',
        'reminder_sent'             => 'Payment reminder sent',
        'overdue_notification_sent' => 'Overdue notification sent',
        'status_changed'            => 'Status changed',
        'customer_changed'          => 'Customer changed',
        'note_added'                => 'Note added',
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

    public function rental()
    {
        return $this->belongsTo(LockerRental::class, 'rental_id');
    }

    /** Polymorphic relation to related record (e.g. LockerRentalPayment, LockerNotificationLog). */
    public function related()
    {
        return $this->morphTo('related', 'related_type', 'related_id');
    }
}
