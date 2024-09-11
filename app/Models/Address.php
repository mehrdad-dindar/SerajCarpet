<?php

namespace App\Models;

use App\Services\AddressService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;
    protected AddressService $addressService;
    protected $guarded;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->addressService = new AddressService();
    }

    protected function googleMap(): Attribute
    {
        return $this->addressService->googleMap();
    }

    protected function location(): Attribute
    {
        return $this->addressService->location();
    }

    public function getFullAddress(): string
    {
        return $this->addressService->getFullAddress($this);
    }
    public function updateAddressGeo($location): void
    {
        $this->addressService->updateAddressGeo($this, $location);
    }
}
