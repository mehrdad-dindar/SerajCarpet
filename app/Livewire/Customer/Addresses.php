<?php

namespace App\Livewire\Customer;

use App\Models\Address;
use Livewire\Component;

class Addresses extends Component
{
    public function render()
    {
        $addresses = auth()->user()->addresses()->get();
        return view('livewire.customer.addresses', [
            'addresses' => $addresses
        ]);
    }
}
