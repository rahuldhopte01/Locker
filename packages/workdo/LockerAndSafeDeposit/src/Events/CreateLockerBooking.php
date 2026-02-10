<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class CreateLockerBooking
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lockerBooking;

    public function __construct($request , $lockerBooking)
    {
        $this->request       = $request;
        $this->lockerBooking = $lockerBooking;
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
