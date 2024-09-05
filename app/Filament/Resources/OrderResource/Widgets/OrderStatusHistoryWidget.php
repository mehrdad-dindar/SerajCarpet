<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Spatie\Activitylog\Models\Activity;

class OrderStatusHistoryWidget extends Widget
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.order-resource.widgets.order-status-history-widget';

    public Order $order;
    public $activities = [];

    public function mount()
    {
        $this->activities = Activity::forSubject($this->order)
            ->latest()
            ->take(20)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
