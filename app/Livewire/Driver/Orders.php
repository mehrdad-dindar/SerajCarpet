<?php

namespace App\Livewire\Driver;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("سفارشات")]
class Orders extends Component
{
    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.orders');
    }

    public function test()
    {
        dd("hehu");
    }
}
