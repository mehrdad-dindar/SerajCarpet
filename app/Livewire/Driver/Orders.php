<?php

namespace App\Livewire\Driver;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("سفارشات")]
class Orders extends Component
{
    public Collection $routeTypes;
    public $opRoute;
    public $shift;
    public $shiftTitle;

    public $orders = [];

    protected $listeners = ['refreshOrderList'];
    public function mount()
    {
        $this->shift = shiftSettings()->getCurrentShift();
        $this->shiftTitle = shiftSettings()->getCurrentShiftTitle();
        $this->opRoute = $this->getDriverRoute();
        $this->getOrders();
    }

    public function refreshOrderList()
    {
        $this->mount();
    }
    private function getDriverRoute()
    {
        $driver = auth('driver')->user();
        return $driver->optimizedRoutes()->whereShift($this->shift)->first();
    }
    private function getOrders(): void
    {
        if (!is_null($this->opRoute)) {
            $this->orders = $this->opRoute->orders;
        } else {
            $this->orders = [];
        }
    }
    #[Layout('driver.layouts.app')]
    public function render(): Application|Factory|View|\Illuminate\View\View
    {
        return view('livewire.driver.orders');
    }
}
