<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderComponent extends Component
{
    public $orders;
    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.order-component');
    }

    public function mount()
    {
        $orderService = new OrderService();
        $this->orders = $orderService->getCustomerOrders();
    }
}
