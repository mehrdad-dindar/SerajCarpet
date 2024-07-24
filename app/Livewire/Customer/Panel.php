<?php

namespace App\Livewire\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Panel extends Component
{
    #[Layout('customer.layouts.app')]
    public function render()
    {
        return view('livewire.customer.panel');
    }
}
