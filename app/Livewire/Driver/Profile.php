<?php

namespace App\Livewire\Driver;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("پروفایل راننده")]
class Profile extends Component
{
    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.profile');
    }
}
