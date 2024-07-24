<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('customer.layouts.app');
    }
}
