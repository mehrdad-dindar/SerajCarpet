<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Events\BulkOrderUpdated;
use App\Models\Driver;
use App\Models\OptimizedRoute;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Settings\ShiftSettings;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\Facades\LogBatch;

class LoadingOrders extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'تحویل سفارشات به راننده (بارگیری)';
    protected static ?string $title = 'بارگیری و تحویل سفارشات به ناوگان';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.loading-orders';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'operation_type' => 'distribution', // 'distribution' (پخش) or 'collection' (جمع‌آوری)
            'shift' => app(ShiftSettings::class)->getCurrentShift(),
            'orders' => [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('DriverAndShift')
                        ->label('انتخاب راننده و شیفت')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(3)->schema([
                                Radio::make('operation_type')
                                    ->label('نوع عملیات بارگیری')
                                    ->options([
                                        'distribution' => 'لیست پخش (فرش‌های شسته شده جهت تحویل به مشتری)',
                                        'collection'   => 'لیست جمع‌آوری (سفارش‌های رزرو شده جهت دریافت از مشتری)',
                                    ])
                                    ->default('distribution')
                                    ->live()
                                    ->columnSpan(3),
                                Select::make('driver_id')
                                    ->label('انتخاب راننده')
                                    ->options(Driver::where('status', 'active')->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('orders', []))
                                    ->columnSpan(1),
                                Select::make('shift')
                                    ->label('شیفت کاری')
                                    ->options(ShiftSettings::getTodayShifts())
                                    ->default(fn () => app(ShiftSettings::class)->getCurrentShift())
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('orders', []))
                                    ->columnSpan(1),
                                Placeholder::make('shift_info')
                                    ->label('وضعیت شیفت جاری')
                                    ->content(fn () => app(ShiftSettings::class)->getCurrentShiftTitle() ?: 'خارج از شیفت رسمی')
                                    ->columnSpan(1),
                            ]),
                        ]),

                    Wizard\Step::make('SelectOrders')
                        ->label('انتخاب سفارشات جهت بارگیری')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->schema([
                            Section::make('سفارش‌های واجد شرایط بارگیری')
                                ->description('سفارش‌هایی که آدرس و مشخصات آن‌ها تایید شده و آماده تخصیص هستند')
                                ->schema([
                                    Actions::make([
                                        FormAction::make('selectAll')
                                            ->label('انتخاب همه سفارش‌ها')
                                            ->icon('heroicon-m-check-badge')
                                            ->action(function (Get $get, Set $set) {
                                                $availableIds = array_keys($this->getAvailableOrdersList($get));
                                                $set('orders', $availableIds);
                                            }),
                                        FormAction::make('deselectAll')
                                            ->label('لغو انتخاب همه')
                                            ->icon('heroicon-m-x-circle')
                                            ->color('danger')
                                            ->action(fn (Set $set) => $set('orders', [])),
                                    ]),
                                    CheckboxList::make('orders')
                                        ->hiddenLabel()
                                        ->options(fn (Get $get) => $this->getAvailableOrdersList($get))
                                        ->descriptions(fn (Get $get) => $this->getOrdersDescriptions($get))
                                        ->columns(2)
                                        ->live()
                                        ->required(),
                                ]),
                        ]),

                    Wizard\Step::make('Confirmation')
                        ->label('تایید نهایی و صدور مانیفست بارگیری')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            Grid::make(3)->schema([
                                Placeholder::make('summary_count')
                                    ->label('تعداد کل سفارشات انتخابی')
                                    ->content(fn (Get $get) => count($get('orders') ?? []) . ' سفارش'),
                                Placeholder::make('summary_driver')
                                    ->label('راننده تحویل‌گیرنده')
                                    ->content(fn (Get $get) => Driver::find($get('driver_id'))?->name ?? '---'),
                                Placeholder::make('summary_shift')
                                    ->label('شیفت کاری انتخابی')
                                    ->content(fn (Get $get) => $get('shift') ?: 'نامشخص'),
                            ]),
                        ]),
                ])
                    ->submitAction(new HtmlString(
                        '<button type="submit" class="fi-btn fi-btn-size-md fi-btn-color-primary relative inline-grid grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg gap-1.5 px-4 py-2 text-sm text-white shadow-sm bg-amber-600 hover:bg-amber-500">
                        <span class="fi-btn-label">ثبت قطعی بارگیری و تحویل به راننده</span>
                    </button>'
                    )),
            ])
            ->statePath('data');
    }

    public function getAvailableOrdersList(Get $get): array
    {
        $operationType = $get('operation_type') ?? 'distribution';
        $driverId = $get('driver_id');

        $query = Order::query()->with(['customer', 'address', 'items.property']);

        if ($operationType === 'distribution') {
            // فرش‌های شسته شده و آماده تحویل در انبار کارخانه
            $targetStatusId = OrderStatus::getIdByName(OrderStatusEnum::READY_FOR_DELIVERY->value);
            $query->where('status_id', $targetStatusId);
        } else {
            // سفارش‌های جدید رزرو شده جهت جمع‌آوری
            $targetStatusId = OrderStatus::getIdByName(OrderStatusEnum::RESERVED->value);
            $query->where('status_id', $targetStatusId);
        }

        if ($driverId) {
            $query->where(function ($q) use ($driverId) {
                $q->whereNull('driver_id')->orWhere('driver_id', $driverId);
            });
        }

        return $query->latest()->take(50)->get()->mapWithKeys(function (Order $order) {
            $customerName = $order->customer?->name ?? 'بدون نام';
            $zone = $order->address?->municipality_zone ? " (منطقه {$order->address->municipality_zone})" : '';
            return [$order->id => "سفارش #{$order->id} - {$customerName}{$zone}"];
        })->toArray();
    }

    public function getOrdersDescriptions(Get $get): array
    {
        $selectedIds = array_keys($this->getAvailableOrdersList($get));
        if (empty($selectedIds)) {
            return [];
        }

        $orders = Order::whereIn('id', $selectedIds)->with(['items.property.serviceItem', 'address'])->get();
        $descriptions = [];

        foreach ($orders as $order) {
            $itemsSummary = $order->items->map(function ($item) {
                return ($item->property?->fullTitle ?? 'فرش') . " ({$item->quantity} تخته/عدد)";
            })->join(' | ');

            $address = $order->address?->getFullAddress() ?? 'فاقد آدرس ثبت‌شده';
            $descriptions[$order->id] = "آدرس: {$address} \nاقلام: " . ($itemsSummary ?: 'سفارش فاقد قلم کالا');
        }

        return $descriptions;
    }

    public function submit(): void
    {
        $state = $this->form->getState();
        $orderIds = $state['orders'] ?? [];
        $driverId = $state['driver_id'] ?? null;
        $operationType = $state['operation_type'] ?? 'distribution';
        $shift = $state['shift'] ?? app(ShiftSettings::class)->getCurrentShift();

        if (empty($orderIds) || !$driverId) {
            Notification::make()->title('خطا')->body('لطفا حداقل یک سفارش و یک راننده را مشخص کنید.')->danger()->send();
            return;
        }

        $targetStatusName = $operationType === 'distribution'
            ? OrderStatusEnum::IN_DISTRIBUTION_LIST->value
            : OrderStatusEnum::IN_COLLECTIVE_LIST->value;

        $targetStatusId = OrderStatus::getIdByName($targetStatusName);

        DB::transaction(function () use ($orderIds, $driverId, $targetStatusId, $shift, $targetStatusName) {
            $orders = Order::whereIn('id', $orderIds)->get();

            LogBatch::startBatch();

            Order::whereIn('id', $orderIds)->update([
                'driver_id' => $driverId,
                'status_id' => $targetStatusId,
                'time_apply_status' => Carbon::now(),
            ]);

            foreach ($orders as $order) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($order)
                    ->withProperties(['driver_id' => $driverId, 'status' => $targetStatusName, 'shift' => $shift])
                    ->log("سفارش به راننده تحویل و به وضعیت [{$targetStatusName}] منتقل شد.");
            }

            LogBatch::endBatch();

            // بروزرسانی روت بهینه نشان برای راننده
            $driver = Driver::find($driverId);
            if ($driver) {
                app(OptimizedRoute::class)->calculateRoute([$driverId]);
            }

            event(new BulkOrderUpdated($orders, $targetStatusId));
        });

        Notification::make()
            ->title('عملیات بارگیری با موفقیت انجام شد')
            ->body(count($orderIds) . ' سفارش به راننده تحویل و در مانیفست راننده قرار گرفت.')
            ->success()
            ->send();

        $this->form->fill([
            'operation_type' => $operationType,
            'driver_id' => null,
            'orders' => [],
            'shift' => $shift,
        ]);
    }
}
