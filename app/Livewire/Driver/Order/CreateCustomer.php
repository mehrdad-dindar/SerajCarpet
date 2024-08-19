<?php

namespace App\Livewire\Driver\Order;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("ثبت کاربر")]
class CreateCustomer extends Component
{
    public $name;
    public $phone;
    public $showModal = false; // وضعیت نمایش مودال

    protected $listeners = ['openModal' => 'showModal', 'closeModal'];

    public function showModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    #[Layout("driver.layouts.app")]
    public function render()
    {
        return view('livewire.driver.order.create-customer');
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|digits:11',
    ];

    public function create()
    {
        $this->validate();

        $customer = Customer::firstOrCreate(
            ['phone' => $this->phone],
            ['name' => $this->name]
        );

        $this->dispatch('customerCreated', $customer->id);

        $this->reset(['name', 'phone']);
        $this->closeModal();
    }
}
