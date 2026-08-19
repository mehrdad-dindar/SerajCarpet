<?php

namespace App\Events;

use App\Models\CallLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $payload;

    public function __construct(public CallLog $callLog)
    {
        $customer = $callLog->customer;

        $lastOrders = [];
        if ($customer) {
            $lastOrders = $customer->orders()
                ->with('status')
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($order) {
                    $rawDate = $order->getRawOriginal('created_at');
                    return [
                        'id'     => $order->id,
                        'status' => $order->status?->label ?? 'نامشخص',
                        'total'  => number_format((int) $order->total) . ' تومان',
                        'date'   => $rawDate ? verta($rawDate)->format('Y/m/d') : (string) $order->created_at,
                    ];
                })->toArray();
        }

        $this->payload = [
            'call_log_id' => $callLog->id,
            'caller_id'   => $callLog->caller_id,
            'extension'   => $callLog->extension ?? 'صف تماس',
            'uniqueid'    => $callLog->uniqueid,
            'customer'    => $customer ? [
                'id'      => $customer->id,
                'name'    => $customer->name ?? 'بدون نام',
                'phone'   => $customer->phone,
                'phone2'  => $customer->phone2,
                'orders'  => $lastOrders,
                'address' => $customer->addresses()->where('is_active', true)->first()?->getFullAddress() ?? 'فاقد آدرس فعال',
            ] : null,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('voip-calls'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'incoming.call';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
