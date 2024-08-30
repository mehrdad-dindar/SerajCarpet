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
    public $orders;

    public function mount()
    {
        $this->orders = auth()->user()->orders()->latest()->get();
        $orderLocations = $this->orders->map(function($order) {
            return [
                'id' => $order->id,
                'latitude' => $order->address->latitude,
                'longitude' => $order->address->longitude,
            ];
        });

        $res = $this->salesman($orderLocations);
        $this->points = $res->getData()->points;
        $this->dispatch('pointsUpdated', $this->points);
    }

    #[Layout("driver.layouts.map")]
    public function render()
    {
        return view('livewire.driver.tasks')->with([
            "orders" => $this->sortOrdersByIndex($this->orders, $this->points),
        ]);
    }

    public function sortOrdersByIndex($orders, $apiResponse)
    {
        // تبدیل پاسخ API به یک مجموعه و مرتب کردن بر اساس index
        $sortedOrders = collect($apiResponse)->map(function($point) use ($orders) {
            // پیدا کردن سفارش مرتبط با این index
            $orderIndex = $point->index;

            // برگرداندن سفارش بر اساس این index
            return $orders[$orderIndex];
        });

        // برگرداندن سفارش‌های مرتب شده
        return $sortedOrders;
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
