<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\Activitylog\Models\Activity;

class OrderLogCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Activity $activity;
    public bool $updateDirection;

    /**
     * Create a new event instance.
     */
    public function __construct(Activity $activity, $updateDirection = true)
    {
        $this->activity = $activity;
        $this->updateDirection = $updateDirection;
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
