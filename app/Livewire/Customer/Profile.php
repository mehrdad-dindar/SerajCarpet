<?php

namespace App\Livewire\Customer;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Profile extends Component
{
    public function mount()
    {
    }

    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.profile');
    }
}
