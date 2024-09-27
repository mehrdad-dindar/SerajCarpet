<?php

namespace App\Livewire\Driver;

use App\Models\OptimizedRoute;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("پیشخوان راننده")]
class Panel extends Component
{
    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.panel');
    }
}
