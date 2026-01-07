<?php

namespace App\Events\CateringOrder;

use App\CateringOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CateringOrderLocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cateringOrder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CateringOrder $cateringOrder)
    {
        $this->cateringOrder = $cateringOrder;
    }
}
