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
                'longitude' => $attributes['longitude']
            ],
            set: fn (array $value) => [
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude']
            ],
        );
    }

    public function getFullAddress(Address $address): string
    {
        $parts = [
            $address->address,
            $address->no ? 'پلاک ' . $address->no : null,
            $address->floor ? 'طبقه ' . $address->floor : null,
            $address->unit ? 'واحد ' . $address->unit : null,
        ];

        $filteredParts = array_filter($parts);

        return implode(' - ', $filteredParts);
    }

    public function getArea(Address $address): string
    {
        $parts = [
            $address->municipality_zone ? 'منطقه '.$address->municipality_zone : null,
            $address->neighbourhood ?? null,
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

    public function getMapUrl(Address $address)
    {
        $lon = $address->longitude;
        $lat = $address->latitude;
        if ($lat && $lon) {
            $tileNumber = $this->getTileNumber($lon, $lat);
        }
        return "https://tile.openstreetmap.org/$tileNumber.png";
    }

    private function getTileNumber($lon, $lat)
    {
        $zoom = 15;
        $xtile = floor((($lon + 180) / 360) * pow(2, $zoom));
        $ytile = floor((1 - log(tan(deg2rad($lat)) + 1 / cos(deg2rad($lat))) / pi()) /2 * pow(2, $zoom));
        return  $zoom . "/" . $xtile . "/" . $ytile;
    }
}
