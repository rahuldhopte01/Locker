<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLockerMaintenance
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lockerMaintenance;

    public function __construct($lockerMaintenance)
    {
        $this->lockerMaintenance = $lockerMaintenance;
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
