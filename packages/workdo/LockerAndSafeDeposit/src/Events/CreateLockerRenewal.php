<?php

namespace Workdo\LockerAndSafeDeposit\Events;

use Illuminate\Queue\SerializesModels;

class CreateLockerRenewal
{
    use SerializesModels;

      /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lockerRenewal;

    public function __construct($request , $lockerRenewal)
    {
        $this->request       = $request;
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
