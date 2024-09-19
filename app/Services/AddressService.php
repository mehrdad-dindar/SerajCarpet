<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AddressService
{
    public function googleMap(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => [
                'mark' => 'نمایش',
            ]
        );
    }

    public function location(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => [
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
            ],
            set: fn (array $value) => [
                'latitude' => $value['lat'],
                'longitude' => $value['lng'],
            ],
        );
    }

    public function getFullAddress(Address $address): string
    {
        $parts = [
            $address->state,
            $address->city,
            $address->address,
            $address->no ? 'پلاک ' . $address->no : null,
            $address->floor ? 'طبقه ' . $address->floor : null,
            $address->unit ? 'واحد ' . $address->unit : null,
        ];

        $filteredParts = array_filter($parts);

        return implode(' - ', $filteredParts);
    }

    public function updateAddressGeo(Address $address, array $points): void
    {
        $address->latitude = $points[0];
        $address->longitude = $points[1];
        $address->save();
    }
}
