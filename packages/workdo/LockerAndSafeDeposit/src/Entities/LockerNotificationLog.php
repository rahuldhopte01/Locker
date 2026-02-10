<?php

namespace Workdo\LockerAndSafeDeposit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LockerNotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'recipient',
        'subject',
        'message',
        'status',
        'reference_type',
        'reference_id',
        'error_message',
        'sent_at',
        'delivered_at',
        'workspace',
        'created_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public static $channels = [
        'email' => 'Email',
        'sms' => 'SMS',
    ];

    public static $statuses = [
        'pending' => 'Pending',
        'sent' => 'Sent',
        'delivered' => 'Delivered',
        'failed' => 'Failed',
    ];

    public function reference()
    {
        return $this->morphTo();
    }
}
