<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BulkOrderUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $oldOrders;
    public Collection $orders;
    public $user;
    public $statusId;
    /**
     * Create a new event instance.
     */
    public function __construct(Collection $orders, $statusId = null)
    {
        $this->oldOrders = $orders;
        $this->orders = Order::whereIn('id', $orders->pluck('id'))->get();
        $this->user = auth()->user();
        $this->statusId = $statusId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
