<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Order $order;
    public $allItems;

    public function mount(Order $order): void
    {
        $this->order = $order;
        $this->allItems = $order->getAllItemsAttribute();
    }

    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.order.show');
    }
}
