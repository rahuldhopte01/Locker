<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLockerCustomer
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lockerCustomer;

    public function __construct($request , $lockerCustomer)
    {
        $this->request        = $request;
        $this->lockerCustomer = $lockerCustomer;
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
