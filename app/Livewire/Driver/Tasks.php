<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use App\Models\Order;
use App\Models\OrderStatus;
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
    public OrderStatus $routeStatus;
    public $selectedOrder = null;
    public $opRoute;
    public $orders;

    public function mount($status_id): void
    {
        $this->getRouteType($status_id);
        $this->opRoute = $this->getDriverRoute();
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

    private function getOrders(): void
    {
        $statuses = [
            OrderStatus::IN_COLLECTIVE_LIST,
            OrderStatus::IN_DISTRIBUTION_LIST,
            OrderStatus::REVISITING_DRIVER,
        ];
        $this->orders = $this->opRoute->orders()
            ->where('status_id', $this->routeStatus->id)
            ->where('time_apply_status', '>=', now());
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

    private function getRouteType($status_id)
    {
        $this->routeStatus = OrderStatus::findOrFail($status_id);
    }

    private function getDriverRoute()
    {
        return auth('driver')->user()->optimizedRoutes()
            ->where('order_status_id', $this->routeStatus->id)
            ->first();
    }
}
