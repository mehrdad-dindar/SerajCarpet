<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Comment;
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
    public $addressData;
    public $commentBody;

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
        $this->getAddressData();
    }

    public function submit()
    {
        if (!empty($this->addressData)) {
            $this->customer->addresses()->update(['is_active' => false]);

            $address = $this->customer->addresses()->updateOrCreate(
                [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                ],
                [
                'state' => $this->addressData['state'],
                'city' => $this->addressData['city'],
                'address' => $this->addressData['formatted_address'],
                'municipality_zone' => $this->addressData['municipality_zone'],
                'neighbourhood' => $this->addressData['neighbourhood'],
                'is_active' => true,
                'is_suggested' => true,
                ]
            );
            if ($address and $this->commentBody) {
                $this->submitAddressComment($address);
            }
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

    private function getAddressData()
    {
        $addressData = $this->reverseGeocoding($this->latitude, $this->longitude)->getData();
        if ($addressData && $addressData->status === 'OK') {
            $this->addressData = [
                'state' => $addressData->state,
                'city' => $addressData->city,
                'formatted_address' => $addressData->formatted_address,
                'municipality_zone' => $addressData->municipality_zone,
                'neighbourhood' => $addressData->neighbourhood,
                'area' => 'منطقه '.$addressData->municipality_zone.' - '.$addressData->neighbourhood
            ];
        } else {
            $this->addressData = [];
        }
    }

    private function submitAddressComment(Address $address)
    {
        $comment = new Comment();
        $comment->body = $this->commentBody;
        $comment->commenter()->associate($this->customer);
        $address->comments()->save($comment);
    }
}
