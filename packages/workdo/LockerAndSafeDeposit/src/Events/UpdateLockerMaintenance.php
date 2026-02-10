<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLockerMaintenance
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lockerMaintenance;

    public function __construct($request , $lockerMaintenance)
    {
        $this->request           = $request;
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
