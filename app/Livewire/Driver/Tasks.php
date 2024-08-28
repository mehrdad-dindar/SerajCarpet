<?php

namespace App\Livewire\Driver;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Tasks extends Component
{
    #[Layout("driver.layouts.map")]
    public function render()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('livewire.driver.tasks')->with([
            "orders" => $orders
        ]);
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
