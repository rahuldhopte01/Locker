<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLockerMembership
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lockerMembership;

    public function __construct($lockerMembership)
    {
        $this->lockerMembership = $lockerMembership;
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
