<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLockerKey
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lockerKey;

    public function __construct($lockerKey)
    {
        $this->lockerKey = $lockerKey;
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
