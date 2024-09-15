<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.order.show');
    }
}
