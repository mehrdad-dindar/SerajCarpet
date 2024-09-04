<?php

namespace App\Livewire\Driver;

use App\Models\Order;
use App\Traits\Neshan;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Component;
use function Laravel\Prompts\error;

class Tasks extends Component
{
    use Neshan;

    public $points = [];
    public $opRoute;
    public $orders;

    public function mount()
    {
        $this->opRoute = auth()->user()->optimizedRoutes()->orderBy('created_at','desc')->first();
        $this->orders = $this->opRoute->orders();

        $this->points = $this->orders->map(function($order) {
            return [
                'id' => $order->id,
                'latitude' => $order->address->latitude,
                'longitude' => $order->address->longitude,
            ];
        });

        $this->dispatch('pointsUpdated', $this->points);
    }

    #[Layout("driver.layouts.map")]
    public function render()
    {
        return view('livewire.driver.tasks');
    }
    public function goToIndex()
    {
        return redirect()->route("driver.panel.index");
    }

    public function makeCall($phoneNumber)
    {
        $this->dispatch('callInitiated', number: intval($phoneNumber));
    }

}
