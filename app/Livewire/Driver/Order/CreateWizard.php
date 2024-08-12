<?php

namespace App\Livewire\Driver\Order;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("ثبت سفارش")]
class CreateWizard extends Component
{
    #[Layout("driver.layouts.app")]
    public function render()
    {
        return view('livewire.driver.order.create-wizard');
    }
}
