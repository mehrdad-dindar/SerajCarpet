<?php

namespace App\Livewire\Driver\Order;

use App\Models\OrderStatus;
use Livewire\Component;

class Card extends Component
{
    public $type;

    public function render()
    {
        return view('livewire.driver.order.card');
    }

    public function typeLabel(): string
    {
        return match ($this->type->name) {
            OrderStatus::IN_COLLECTIVE_LIST => 'لیست جمعی',
            OrderStatus::IN_DISTRIBUTION_LIST => 'لیست پخشی',
            OrderStatus::REVISITING_DRIVER => 'مراجعه مجدد',
            default => 'وضعیت نامشخص'
        };
    }

    public function getRoute()
    {
        return redirect()->route('driver.tasks', $this->type->id);
    }

    public function getOrdersCount()
    {
        $driver = auth('driver')->user();
        $type = $this->type;
        $optimizedRoute = $driver->optimizedRoutes()
            ->where('order_status_id', $type->id)
            ->first();
        if ($optimizedRoute) {
            return $optimizedRoute->orders()->count();
        }
        return 0;
    }
}
