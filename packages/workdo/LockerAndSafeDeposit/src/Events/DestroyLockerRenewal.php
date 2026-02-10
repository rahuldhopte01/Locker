<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLockerRenewal
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lockerRenewal;

    public function __construct($lockerRenewal)
    {
        $this->lockerRenewal = $lockerRenewal;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
