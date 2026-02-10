<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class CreateLockerMembership
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lockerMembership;

    public function __construct($request , $lockerMembership)
    {
        $this->request          = $request;
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
