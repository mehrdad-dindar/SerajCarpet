<?php

namespace App\Events;

use App\Models\Customer;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer)
    {
        $admins = User::role('panel_user')->get();
        $this->customer = $customer;
        Notification::make()
            ->title('تماس ورودی جدید')
            ->body("تماس از {$customer->name} ({$customer->phone})")
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
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('calls'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'incoming-call'; // نام رویداد در کلاینت
    }
}
