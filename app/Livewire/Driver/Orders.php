<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Settings\ShiftSettings;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Verta;
use function Psy\sh;

#[Title("سفارشات")]
class Orders extends Component
{
    public Collection $routeTypes;
    public $opRoute;
    public $shift;

    public $orders = [];
    public function mount()
    {
        $this->shift = shiftSettings()->getCurrentShift();
        $this->opRoute = $this->getDriverRoute();
        $this->getOrders();
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

    private function getShift()
    {
        $this->shift = null;
        //        dd(shiftSettings()->getCurrentShift());

        $shiftHours = shiftSettings()->shifts;
        $now = Verta::now();
        $dayShift = array_filter($shiftHours, fn ($item) => $item['day'] == $now->dayOfWeek);
        $shiftDetails = reset($dayShift);
        if ($shiftDetails) {
            $morningStart = Verta::createFromFormat('H:i', $shiftDetails['morning_start']);
            dd($shiftDetails);
            $morningEnd = Verta::createFromFormat('H:i', $shiftDetails['morning_end']);
            $afternoonStart = Verta::createFromFormat('H:i', $shiftDetails['afternoon_start']);
            $afternoonEnd = Verta::createFromFormat('H:i', $shiftDetails['afternoon_end']);

            if ($now->between($morningStart, $morningEnd)) {
                $this->shift = OptimizedRoute::MORNING_SHIFT;
            } elseif ($now->between($afternoonStart, $afternoonEnd)) {
                $this->shift = OptimizedRoute::AFTERNOON_SHIFT;
            }
        }
    }
}
