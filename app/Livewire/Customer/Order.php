<?php

namespace App\Livewire\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Order extends Component
{
    #[Layout("customer.layouts.app")]
    public function render()
    {
        $orders = auth('customer')->user()->orders()->paginate(10);
        return view('livewire.customer.order')->with([
            "orders" => $orders
        ]);
    }
}
