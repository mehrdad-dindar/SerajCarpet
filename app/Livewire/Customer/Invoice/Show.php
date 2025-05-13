<?php

namespace App\Livewire\Customer\Invoice;

use App\Models\Invoice;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{

    public Invoice $invoice;
    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }
    #[Layout("customer.layouts.app")]
    public function render()
    {
        return view('livewire.customer.invoice.show');
    }

    public function purchase()
    {
        $this->redirect(route('customer.panel.invoice.purchase', [$this->invoice]));
    }
}
