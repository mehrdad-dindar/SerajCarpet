<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Traits\Neshan;
use Hashids\Hashids;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('ثبت موقعیت مکانی')]
class SetLocation extends Component
{
    use LivewireAlert;
    use Neshan;

    public $latitude;

    public $longitude;

    public Customer $customer;

    protected $listeners = [
        'updateLocation' => 'updateLocation',
    ];

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

    public function updateLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function submit()
    {

        $addressData = $this->reverseGeocoding($this->latitude, $this->longitude)->getData();

        if ($addressData->status == 'OK') {
            $this->customer->addresses()->update(['is_active' => false]);

            $address = $this->customer->addresses()->create([
                'state' => $addressData->state,
                'city' => $addressData->city,
                'address' => $addressData->formatted_address,
                'municipality_zone' => $addressData->municipality_zone,
                'neighbourhood' => $addressData->neighbourhood,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'is_active' => true,
                'is_suggested' => true,
            ]);
            // get latest order of this customer
            $order = $this->customer->orders()->latest()->firstOrFail();
            if ($order) {
                $order->address()->associate($address);
                $order->save();
            }

            $this->alert(
                'success',
                __("Your location has been successfully registered \nThe next steps will be informed via SMS"),
                [
                    'position' => 'center',
                    'timer' => 5000,
                ]
            );
        }
    }
}
