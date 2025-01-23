<?php

namespace App\Filament\Resources\AddressResource\Pages;

use App\Filament\Resources\AddressResource;
use App\Forms\Components\AddressForm;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAddress extends CreateRecord
{
    protected static string $resource = AddressResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return AddressForm::mutate($data);
    }

    /**
     * Get the redirect URL after the order creation.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
