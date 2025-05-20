<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use http\Client\Response;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Order $order;
    public $allItems;

    public function mount(Order $order)
    {
        $this->order = $order;
        if ($order->customer_id !== auth()->guard('customer')->id()) {
            abort(404);
        }
        $this->allItems = $order->getAllItemsAttribute();
    }

    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.order.show');
    }
}
