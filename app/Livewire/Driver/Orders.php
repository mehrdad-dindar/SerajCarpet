<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("سفارشات")]
class Orders extends Component
{
    public Collection $routeTypes;
    use WithPagination;

    public function mount()
    {
        $this->routeTypes = OptimizedRoute::getRouteTypes();
    }
    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.orders')->with([
            "orders" => $this->getDriverOrders()
        ]);
    }

    private function getDriverOrders()
    {
        $driver = OptimizedRoute::getDriverRoute();
        return $driver->orders();
    }
}
