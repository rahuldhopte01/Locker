<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class CreateLockerKey
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lockerKey;

    public function __construct($request , $lockerKey)
    {
        $this->request   = $request;
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
