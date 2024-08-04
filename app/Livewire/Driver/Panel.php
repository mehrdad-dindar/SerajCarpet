<?php

namespace App\Livewire\Driver;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Panel extends Component
{
    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.panel');
    }
}
