<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Casts\Attribute;

class AddressService
{
    public function googleMap(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => [
                'mark' => 'نمایش'
            ]
        );
    }

    public function location(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => [
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude']
            ],
            set: fn (array $value) => [
                'latitude' => $value['lat'],
                'longitude' => $value['lng']
            ],
        );
    }
}
