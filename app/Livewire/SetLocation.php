<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Traits\Neshan;
use Hashids\Hashids;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("ثبت موقعیت مکانی")]
class SetLocation extends Component
{
    use Neshan;
    use LivewireAlert;

    public $id;
    public $latitude;
    public $longitude;
    protected $listeners = [
        'updateLocation' => 'updateLocation',
    ];

    public function mount($id)
    {
        $this->id = $id;
    }

    #[Layout("layouts.map")]
    public function render()
    {
        $hashid = new Hashids('', 6);
        $customerID = $hashid->decode($this->id)[0];
        $customer = Customer::findOrFail($customerID);

        return view('livewire.set-location')
            ->with([
                'customer' => $customer,
                'hashid' => $this->id,
            ]);
    }

    public function updateLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function submit()
    {
        $hashid = new Hashids('', 6);
        $customerID = $hashid->decode($this->id)[0];
        $customer = Customer::findOrFail($customerID);
        $addressData = $this->reverseGeocoding($this->latitude, $this->longitude)->getData();

        if ($addressData->status == 'OK') {
            $customer->addresses()->update(['is_active' => false]);

            $customer->addresses()->create([
                'state' => $addressData->state,
                'city' => $addressData->city,
                'address' => $addressData->formatted_address,
                'municipality_zone' => $addressData->municipality_zone,
                'neighbourhood' => $addressData->neighbourhood,
                'is_active' => true,
                'is_suggested' => true,
            ]);
            $this->alert('success', __("Your location has been successfully registered \nThe next steps will be informed via SMS"),[
                'position' => 'center',
                'timer' => 5000,
            ]);
        }
    }
}
