<?php

namespace App\Livewire\Driver;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("سفارشات")]
class Orders extends Component
{
    #[Layout('driver.layouts.app')]
    public function render()
    {
        $orders = Order::where('driver_id',auth('driver')->user()->id)->latest()->paginate(10);;
        return view('livewire.driver.orders')->with([
            "orders" => $orders
        ]);
    }
}
