<?php

namespace App\Livewire\Driver\Order;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Collection;
use Livewire\Component;

class Grid extends Component
{
    public $orders;

    public function render()
    {
        return view('livewire.driver.order.grid')->with([
            "arrangedOrders" => $this->getOrders()
        ]);
    }
    private function getOrders()
    {
        return Order::whereIn('id', $this->orders)
            ->whereHas(
                'status',
                fn ($q) => $q->whereIn('name', [
                    OrderStatus::RESERVED,
                    OrderStatus::IN_DISTRIBUTION_LIST,
                    OrderStatus::IN_COLLECTIVE_LIST,
                    OrderStatus::REVISITING_DRIVER
                ])
            )
            ->orderByRaw('FIELD(id, ' . implode(',', $this->orders) . ')')->get();
    }
}
