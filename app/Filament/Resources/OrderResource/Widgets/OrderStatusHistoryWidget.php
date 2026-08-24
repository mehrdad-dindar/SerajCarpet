<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;

class OrderStatusHistoryWidget extends Widget
{
    protected static string $view = 'filament.resources.order-resource.widgets.order-status-history-widget';

    public ?Order $record = null;
    public ?Order $order = null;

    public function getOrderModel(): ?Order
    {
        return $this->record ?? $this->order;
    }

    public function getActivities()
    {
        $order = $this->getOrderModel();
        if (!$order) {
            return collect();
        }

        return Activity::forSubject($order)
            ->latest()
            ->take(30)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
