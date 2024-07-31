<?php

namespace App\Livewire\Customer;

use App\Models\Address;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Addresses extends DataTableComponent
{
//    public function render()
//    {
//        $addresses = auth()->user()->addresses()->get();
//        return view('livewire.customer.addresses', [
//            'addresses' => $addresses
//        ]);
//    }

    protected $model = Address::class;


    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('State')
                ->sortable(),
        ];
    }
}
