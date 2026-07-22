<?php

namespace App\Events;

use App\Models\CallLog;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CallLog $callLog;

    /**
     * Create a new event instance.
     */
    public function __construct(CallLog $callLog)
    {
        $this->callLog = $callLog;

        $admins = User::role('panel_user')->get();
        $customer = $callLog->customer;

        Notification::make()
            ->title('تماس ورودی جدید')
            ->body("تماس از {$customer?->name} ({$customer->phone})")
            ->warning()
            ->icon('heroicon-o-phone-arrow-down-left')
            ->iconColor(Color::Green)
            ->iconSize(IconSize::Large)
            ->duration(null)
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->link()
                    ->url("/admin/customers/{$customer->id}/edit")
                    ->label('مشاهده مشتری'),
            ])
            ->broadcast($admins)
            ->send();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('calls');
    }

    public function broadcastWith(): array
    {
        return [
            'customer' => [
                'id' => $this->callLog->customer?->id,
                'name' => $this->callLog->customer?->name ?? 'ناشناس',
                'phone' => $this->callLog->caller_id,
            ],
            'call_log_id' => $this->callLog->id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'incoming-call'; // نام رویداد در کلاینت
    }
}
