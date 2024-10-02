<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use function Psy\sh;

#[Title("سفارشات")]
class Orders extends Component
{
    public Collection $routeTypes;
    public $opRoute;

    public Collection $orders;
    public function mount()
    {
        $this->opRoute = $this->getDriverRoute();
        $this->getOrders();
    }
    private function getDriverRoute()
    {
        $currentHour = Carbon::now()->hour;
        $shift = $currentHour <= 14 ? OptimizedRoute::MORNING_SHIFT : OptimizedRoute::AFTERNOON_SHIFT;

        return auth('driver')->user()->optimizedRoutes()
            ->whereShift($shift)
            ->first();
    }
    private function getOrders(): void
    {
        if (!is_null($this->opRoute)) {
            $this->orders = $this->opRoute->orders();
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
