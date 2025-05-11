<?php

namespace App\Livewire\Customer;

use App\Services\InvoiceService;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Invoice extends Component
{
    public $invoices;

    public function mount()
    {
        $this->invoices = auth()->guard('customer')->user()->invoices;
    }
    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.invoice');
    }
}
