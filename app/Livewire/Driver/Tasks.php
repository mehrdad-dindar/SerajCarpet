<?php

namespace App\Livewire\Driver;

use App\Models\Order;
use App\Traits\Neshan;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Attributes\Layout;
use Livewire\Component;
use function Laravel\Prompts\error;

class Tasks extends Component
{
    use Neshan;

    public $points = [];
    public $selectedOrder = null;
    public $opRoute;
    public $orders;

    public function mount(): void
    {
        $this->getOptimizedRoute();
        $this->getOrders();
        $this->getPoints();

        $this->dispatch('pointsUpdated', $this->points);
    }

    #[Layout("driver.layouts.map")]
    public function render()
    {
        return view('livewire.driver.tasks');
    }
    public function goToIndex()
    {
        return redirect()->to(route("driver.panel.index"));
    }

    public function makeCall($phoneNumber): void
    {
        $this->dispatch('callInitiated', number: intval($phoneNumber));
    }

    private function getOptimizedRoute(): void
    {
        $this->opRoute = auth()->user()->optimizedRoutes()
            ->orderBy('created_at', 'desc')->first();
    }

    private function getOrders(): void
    {
        $this->orders = $this->opRoute->orders();
    }

    private function getPoints(): void
    {
        $this->points = $this->orders->map(
            function ($order) {
                return [
                    'id' => $order->id,
                    'latitude' => $order->address->latitude,
                    'longitude' => $order->address->longitude,
                ];
            }
        );
    }

    public function showOrderWizard($orderId): void
    {
        $this->selectedOrder = $this->orders->firstWhere('id', $orderId);
    }
}
