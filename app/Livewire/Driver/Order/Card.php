<?php

namespace App\Livewire\Driver\Order;

use App\Models\OptimizedRoute;
use App\Models\OrderStatus;
use Livewire\Component;
use WireUi\Actions\Notification;

class Card extends Component
{
    public $type;
    public $ordersCount;

    public function mount()
    {
        $this->ordersCount = OptimizedRoute::getOrdersCount($this->type);
    }
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
        if ($this->ordersCount) {
            return redirect()->route('driver.tasks', $this->type->id);
        }
        return false;
    }
}
