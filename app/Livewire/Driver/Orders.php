<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("سفارشات")]
class Orders extends Component
{
    public Collection $routeTypes;
    public $opRoute;

    public $selectedType = null;
    public Collection $orders;
    public function mount()
    {
        $this->routeTypes = OptimizedRoute::getRouteTypes();
    }

    public function selectCard(OrderStatus $type): void
    {
        $this->selectedType = $type;
        $this->opRoute = $this->getDriverRoute();
        $this->getOrders();
    }
    private function getDriverRoute()
    {
        return auth('driver')->user()->optimizedRoutes()
            ->where('order_status_id', $this->selectedType->id)
            ->first();
    }
    private function getOrders(): void
    {
        if (!is_null($this->opRoute)) {
            $this->orders = $this->opRoute->orders()
                ->where('status_id', $this->selectedType->id)
                ->where('time_apply_status', '>=', now());
        } else {
            $this->reset('orders');

        }
    }
    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.orders');
    }
}
