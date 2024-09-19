<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Traits\Neshan;
use Hashids\Hashids;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use function Laravel\Prompts\warning;

#[Title('ثبت موقعیت مکانی')]
class SetLocation extends Component
{
    use LivewireAlert;
    use Neshan;

    public $latitude;

    public $longitude;

    public Customer $customer;

    public function mount($id)
    {
        $this->getCustomer($id);
    }

    private function getCustomer($id)
    {
        $hashid = new Hashids('', 6);
        $customerID = $hashid->decode($id)[0];
        $this->customer = Customer::findOrFail($customerID);
    }

    #[Layout('layouts.map')]
    public function render()
    {
        return view('livewire.set-location');
    }

    #[On("updateCustomerLocation")]
    public function updateLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->submit();
    }

    public function submit()
    {
        $addressData = $this->reverseGeocoding($this->latitude, $this->longitude)->getData();
        if ($addressData && $addressData->status === 'OK') {
            $this->customer->addresses()->update(['is_active' => false]);

            $address = $this->customer->addresses()->updateOrCreate(
                [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                ],
                [
                'state' => $addressData->state,
                'city' => $addressData->city,
                'address' => $addressData->formatted_address,
                'municipality_zone' => $addressData->municipality_zone,
                'neighbourhood' => $addressData->neighbourhood,
                'is_active' => true,
                'is_suggested' => true,
                ]
            );
            // get latest order of this customer
            $order = $this->customer->orders()->latest()->first();
            if ($order) {
                $order->address()->associate($address);
                $order->save();
                $this->alert(
                    'success',
                    __("Your location has been successfully registered \nThe next steps will be informed via SMS"),
                    [
                        'position' => 'center',
                        'timer' => 5000,
                    ]
                );
            } else {
                $this->alert(
                    'error',
                    __("Unfortunately, your order was not found. Please contact support"),
                    [
                        'position' => 'center',
                        'timer' => 5000,
                    ]
                );
            }
        }
    }
}
