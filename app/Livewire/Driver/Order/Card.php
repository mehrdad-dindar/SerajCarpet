<?php

namespace App\Livewire\Driver\Order;

use App\Models\OrderStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;

class Card extends Component
{
    public $type;
    public function render()
    {
        return view('livewire.driver.order.card');
    }

    public function typeLabel(): string
    {
        return match ($this->type->name) {
            OrderStatus::IN_COLLECTIVE_LIST => "لیست جمعی",
            OrderStatus::IN_DISTRIBUTION_LIST => "لیست پخشی",
            OrderStatus::REVISITING_DRIVER => "مراجعه مجدد",
            default => 'وضعیت نامشخص'
        };
    }

    public function getRoute()
    {
        return redirect()->route("driver.tasks", $this->type->id);
    }
}
