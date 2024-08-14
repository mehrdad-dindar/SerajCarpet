<?php

namespace App\Livewire\Driver\Order\Steps;

use App\Models\Customer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class SelectCustomerStepComponent extends StepComponent
{
    public $customer_id;
    public $icon = "home";

    protected $listeners = ['customerCreated' => 'addCustomer'];

    public function addCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            //
            /*$this->customers->push($customer);*/
            $this->customer_id = $customerId;
        }
    }

    public function render()
    {
        return view('livewire.driver.order.steps.select-customer-step-component');
    }

    public array $rules = [
        'customer_id' => ['required','exists:customers,id']
    ];

    public function submit()
    {
        $this->validate();

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Customer"),
            'icon' => 'user-plus',
        ];
    }
}
