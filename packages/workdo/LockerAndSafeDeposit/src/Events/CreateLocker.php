<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class CreateLocker
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $locker;

    public function __construct($request , $locker)
    {
        $this->request = $request;
        $this->locker  = $locker;
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
