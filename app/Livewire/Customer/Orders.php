<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Orders extends Component
{
    public $orders;
    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.orders');
    }

    public function mount()
    {
        $orderService = new OrderService();
        $this->orders = $orderService->getCustomerOrders();
    }
}
