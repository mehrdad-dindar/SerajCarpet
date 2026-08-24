<?php

namespace App\Livewire\Customer;

use App\Models\Address;
use App\Traits\Neshan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("دفترچه آدرس‌های من")]
#[Layout('customer.layouts.app')]
class Addresses extends Component
{
    use LivewireAlert, Neshan;

    public bool $showModal = false;
    public ?int $editingAddressId = null;

    public string $state = 'تهران';
    public string $city = 'تهران';
    public string $address = '';
    public string $no = '';
    public string $floor = '';
    public string $unit = '';
    public ?string $municipality_zone = null;
    public ?string $neighbourhood = null;
    public ?string $description = null;
    public float $latitude = 35.69974184;
    public float $longitude = 51.33805990;
    public bool $is_active = false;

    protected function rules(): array
    {
        return [
            'address' => 'required|string|min:5|max:255',
            'no'      => 'required|string|max:10',
            'floor'   => 'nullable|string|max:10',
            'unit'    => 'nullable|string|max:10',
            'latitude' => 'required|numeric',
            'longitude'=> 'required|numeric',
        ];
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingAddressId', 'address', 'no', 'floor', 'unit', 'description', 'municipality_zone', 'neighbourhood']);
        $this->latitude = 35.69974184;
        $this->longitude = 51.33805990;
        $this->is_active = false;
        $this->showModal = true;
        $this->dispatch('initMapPicker', lat: $this->latitude, lng: $this->longitude);
    }

    public function editAddress(int $id): void
    {
        $addr = auth('customer')->user()->addresses()->findOrFail($id);
        $this->editingAddressId = $addr->id;
        $this->address = $addr->address ?? '';
        $this->no = $addr->no ?? '';
        $this->floor = $addr->floor ?? '';
        $this->unit = $addr->unit ?? '';
        $this->description = $addr->description ?? '';
        $this->latitude = $addr->latitude ?? 35.6997;
        $this->longitude = $addr->longitude ?? 51.3381;
        $this->municipality_zone = $addr->municipality_zone;
        $this->neighbourhood = $addr->neighbourhood;
        $this->is_active = (bool) $addr->is_active;

        $this->showModal = true;
        $this->dispatch('initMapPicker', lat: $this->latitude, lng: $this->longitude);
    }

    public function setCoordinates(float $lat, float $lng): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;

        // فراخوانی سرویس معکوس نشان برای خواندن اتوماتیک آدرس
        $geo = self::reverseGeocoding($lat, $lng)->getData(true);
        if (($geo['status'] ?? '') === 'OK') {
            $this->address = $geo['formatted_address'] ?? $this->address;
            $this->municipality_zone = $geo['municipality_zone'] ?? null;
            $this->neighbourhood = $geo['neighbourhood'] ?? null;
            $this->state = $geo['state'] ?? 'تهران';
            $this->city = $geo['city'] ?? 'تهران';
        }
    }

    public function save(): void
    {
        $this->validate();
        $customer = auth('customer')->user();

        if ($this->is_active) {
            $customer->addresses()->update(['is_active' => false]);
        }

        $customer->addresses()->updateOrCreate(
            ['id' => $this->editingAddressId],
            [
                'state'            => $this->state,
                'city'             => $this->city,
                'address'          => $this->address,
                'no'               => $this->no,
                'floor'            => $this->floor,
                'unit'             => $this->unit,
                'municipality_zone'=> $this->municipality_zone,
                'neighbourhood'    => $this->neighbourhood,
                'description'      => $this->description,
                'latitude'         => $this->latitude,
                'longitude'        => $this->longitude,
                'is_active'        => $this->is_active,
                'location_type'    => 2, // CUSTOMER
            ]
        );

        $this->showModal = false;
        $this->alert('success', 'آدرس شما با موفقیت ذخیره شد.');
    }

    public function makeDefault(int $id): void
    {
        $customer = auth('customer')->user();
        $customer->addresses()->update(['is_active' => false]);
        $customer->addresses()->where('id', $id)->update(['is_active' => true]);

        $this->alert('success', 'آدرس پیش‌فرض تغییر یافت.');
    }

    public function deleteAddress(int $id): void
    {
        auth('customer')->user()->addresses()->where('id', $id)->delete();
        $this->alert('success', 'آدرس حذف شد.');
    }

    public function render()
    {
        return view('livewire.customer.addresses', [
            'addresses' => auth('customer')->user()->addresses()->latest()->get(),
        ]);
    }
}
