<?php

namespace App\Livewire\Customer;

use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Panel extends Component
{
    public $wallet = 0 ;
    public $ordersCount;

    public function mount()
    {
        $this->ordersCount = $this->getOrdersCount();
    }
    #[Layout('customer.layouts.app')]
    public function render()
    {
        return view('livewire.customer.panel');
    }

    private function getOrdersCount()
    {
        $orderService = new OrderService();
        return $orderService->orders->count();
    }
}
