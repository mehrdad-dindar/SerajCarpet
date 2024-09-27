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

    public function getRoute()
    {
//        if ($this->ordersCount) {
//            return redirect()->route('driver.tasks', $this->type->id);
//        }
        return false;
    }
}
