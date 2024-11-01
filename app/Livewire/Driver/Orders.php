<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Settings\ShiftSettings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    public $orders = [];
    public function mount(ShiftSettings $shiftSettings)
    {
        $this->opRoute = $this->getDriverRoute($shiftSettings);
        $this->getOrders();
    }
    private function getDriverRoute($shiftSettings)
    {
        $shiftHours = $shiftSettings->shift_hours;
        $now = Verta::now();

        $dayShift = array_filter($shiftHours, fn ($item) => $item['day'] == $now->dayOfWeek);
        $shiftDetails = reset($dayShift);

        $shift = null;
        if ($shiftDetails) {

            $morningStart = Verta::createFromFormat('H:i', $shiftDetails['morning_start']);
            $morningEnd = Verta::createFromFormat('H:i', $shiftDetails['morning_end']);
            $afternoonStart = Verta::createFromFormat('H:i', $shiftDetails['afternoon_start']);
            $afternoonEnd = Verta::createFromFormat('H:i', $shiftDetails['afternoon_end']);

            if ($now->between($morningStart,$morningEnd)) {
                $shift = OptimizedRoute::MORNING_SHIFT;
            } elseif ($now->between($afternoonStart,$afternoonEnd)) {
                $shift = OptimizedRoute::AFTERNOON_SHIFT;
            }
        }

        if (is_null($shift)) {
            return null;
        }

        $driver = auth('driver')->user();
        return $driver->optimizedRoutes()->whereShift($shift)->first();
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
    public function render()
    {
        return view('livewire.driver.orders');
    }
}
