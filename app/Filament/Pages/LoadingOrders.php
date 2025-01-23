<?php

namespace App\Filament\Pages;

use App\Models\Driver;
use App\Models\Order;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class LoadingOrders extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static string $view = 'filament.pages.loading-orders';

    protected static ?string $title = 'تحویل سفارشات به راننده';

    public $driver_id;

    public $opRoute;

    public $orders;

    public $ordersList;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Driver')
                        ->translateLabel()
                        ->schema([
                            Select::make('driver_id')
                                ->label('انتخاب راننده')
                                ->options(Driver::pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ]),
                    Wizard\Step::make('Orders')
                        ->translateLabel()
                        ->schema([
                            CheckboxList::make('orders')
                                ->label('سفارشات لیست پخشی')
                                ->options(fn (Get $get) => $this->driver_id ? $this->getAvailableOrders() : [])
                                ->descriptions(fn (Get $get) => $this->driver_id ? $this->getOrdersDescriptions() : []),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button type="submit" color="primary">سفارش‌ها به راننده تحویل داده شد</x-filament::button>
                    BLADE
                ))),
            ]);
    }

    private function getAvailableOrders(): array
    {
        $this->getOrders();
        return $this->orders->pluck('customer.name', 'id')->toArray();
    }

    private function getOrders(): void
    {
        $driver = Driver::find($this->driver_id);
        if ($driver) {
            $this->getDriverOrders($driver);
            $this->orders = Order::whereIn('id', $this->ordersList)->with('customer')->get();
        }
    }

    private function getDriverOrders(Driver $driver)
    {
        $shift = shiftSettings()->getCurrentShift();
        $this->opRoute = $driver->optimizedRoutes()->whereShift($shift)->first();

        if (! is_null($this->opRoute)) {
            $this->ordersList = $this->opRoute->orders;
        } else {
            $this->ordersList = [];
        }
    }

    private function getOrdersDescriptions(): array
    {
        $data = [];
        foreach ($this->orders as $order) {
            $uniqueItems = $order->items
                ->pluck('property.serviceItem.name')
                ->unique()
                ->join(' - ');
            $data[$order->id] = "شماره سفارش : $order->id - ";
            if ($uniqueItems) {
                $data[$order->id] .= "موارد سفارش : ".$order->items->count() . ' مورد '.$uniqueItems;
            }
        }
        return $data;
    }

    public function submit(): void
    {
        // TODO: Save Log
        $formData = $this->form->getState();
        dd($formData['driver_id']);
    }
}
