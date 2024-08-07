<?php

namespace App\Livewire;

use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\On;
use Livewire\Component;

class Sample extends Component
{
    public function render()
    {
        return view('livewire.sample');
    }

    #[NoReturn] #[On('echo:publicChannel,Test')]
    public function dump(): void
    {
        info('its work!');
        dd('dump');
    }
}
