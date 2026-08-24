<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class NeshanMap extends Field
{
    protected string $view = 'forms.components.neshan-map';

    protected array $defaultLocation = ['lat' => 35.6997, 'lng' => 51.3381];

    public function defaultLocation(float $latitude, float $longitude): static
    {
        $this->defaultLocation = [
            'lat' => $latitude,
            'lng' => $longitude,
        ];

        return $this;
    }

    public function getDefaultLocation(): array
    {
        return $this->defaultLocation;
    }
}
