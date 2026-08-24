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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
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
        // پاکسازی کش وضعیت‌ها جهت همگام‌سازی فوری
        Cache::forget('order_statuses_all');

        $this->form->fill([
            'operation_type' => 'distribution',
            'shift' => app(ShiftSettings::class)->getCurrentShift(),
            'status_filter' => 'auto',
            'ignore_assigned_drivers' => true,
            'orders' => [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // گام ۱: انتخاب راننده و شیفت
                    Wizard\Step::make('DriverAndShift')
                        ->label('۱. انتخاب راننده و شیفت')
                        ->icon('heroicon-o-user')
                        ->description('مشخص کردن راننده تحویل‌گیرنده و شیفت کاری')
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
                                    ->afterStateUpdated(fn (Set $set) => $set('orders', []))
                                    ->columnSpan(3),
                                Select::make('driver_id')
                                    ->label('انتخاب راننده')
                                    ->options(Driver::where('status', 'active')->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),
                                Select::make('shift')
                                    ->label('شیفت کاری')
                                    ->options(ShiftSettings::getTodayShifts())
                                    ->default(fn () => app(ShiftSettings::class)->getCurrentShift())
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),
                                Placeholder::make('shift_info')
                                    ->label('وضعیت شیفت جاری')
                                    ->content(fn () => app(ShiftSettings::class)->getCurrentShiftTitle() ?: 'شیفت عادی')
                                    ->columnSpan(1),
                            ]),
                        ]),

                    // گام ۲: انتخاب سفارشات
                    Wizard\Step::make('SelectOrders')
                        ->label('۲. انتخاب سفارشات')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->description('مشاهده و انتخاب سفارش‌های آماده بارگیری')
                        ->schema([
                            Section::make('فیلتر و مدیریت سفارش‌ها')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Select::make('status_filter')
                                            ->label('فیلتر وضعیت سفارشات')
                                            ->options(function (Get $get) {
                                                $options = [
                                                    'auto' => 'پیش‌فرض بر اساس نوع عملیات (' . ($get('operation_type') === 'distribution' ? 'آماده تحویل' : 'رزرو اولیه') . ')',
                                                    'all'  => 'نمایش همه وضعیت‌ها (بدون فیلتر وضعیت)',
                                                ];
                                                foreach (OrderStatus::all() as $st) {
                                                    $options[$st->id] = "فقط وضعیت: {$st->label}";
                                                }
                                                return $options;
                                            })
                                            ->default('auto')
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('orders', []))
                                            ->columnSpan(2),
                                        Toggle::make('ignore_assigned_drivers')
                                            ->label('شامل سفارش‌هایی که قبلاً راننده داشته‌اند')
                                            ->default(true)
                                            ->live()
                                            ->columnSpan(1),
                                    ]),
                                ]),

                            Section::make('لیست سفارش‌های موجود')
                                ->schema([
                                    Actions::make([
                                        FormAction::make('selectAll')
                                            ->label('انتخاب همه سفارش‌های موجود')
                                            ->icon('heroicon-m-check-badge')
                                            ->color('primary')
                                            ->action(function (Get $get, Set $set) {
                                                $availableIds = array_keys($this->getAvailableOrdersList($get));
                                                $set('orders', $availableIds);
                                            }),
                                        FormAction::make('deselectAll')
                                            ->label('لغو انتخاب همه')
                                            ->icon('heroicon-m-x-circle')
                                            ->color('gray')
                                            ->action(fn (Set $set) => $set('orders', [])),
                                    ]),

                                    CheckboxList::make('orders')
                                        ->hiddenLabel()
                                        ->options(fn (Get $get) => $this->getAvailableOrdersList($get))
                                        ->descriptions(fn (Get $get) => $this->getOrdersDescriptions($get))
                                        ->columns(2)
                                        ->live()
                                        ->required()
                                        ->helperText(function (Get $get) {
                                            $count = count($this->getAvailableOrdersList($get));
                                            if ($count === 0) {
                                                return new HtmlString('<span class="text-danger-600 font-bold">⚠️ هیچ سفارشی با شرایط انتخابی یافت نشد. می‌توانید از فیلتر بالا گزینه «نمایش همه وضعیت‌ها» را انتخاب کنید.</span>');
                                            }
                                            return "تعداد {$count} سفارش واجد شرایط یافت شد.";
                                        })
                                        ->validationMessages([
                                            'required' => 'لطفاً حداقل یک سفارش را برای بارگیری تیک بزنید.',
                                        ]),
                                ]),
                        ]),

                    // گام ۳: تایید نهایی و صدور مانیفست
                    Wizard\Step::make('Confirmation')
                        ->label('۳. بررسی و صدور مانیفست')
                        ->icon('heroicon-o-check-badge')
                        ->description('بازبینی نهایی و تحویل قطعی به راننده')
                        ->schema([
                            Section::make('خلاصه مانیفست بارگیری')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Placeholder::make('summary_count')
                                            ->label('تعداد کل سفارش‌های بارگیری‌شده')
                                            ->content(fn (Get $get) => count($get('orders') ?? []) . ' سفارش'),
                                        Placeholder::make('summary_driver')
                                            ->label('راننده مسئول')
                                            ->content(fn (Get $get) => Driver::find($get('driver_id'))?->name ?? '---'),
                                        Placeholder::make('summary_shift')
                                            ->label('شیفت کاری')
                                            ->content(fn (Get $get) => $get('shift') ?: 'نامشخص'),
                                    ]),
                                ]),
                        ]),
                ])
                    ->nextAction(
                        fn (FormAction $action) => $action
                            ->label('گام بعدی')
                            ->icon('heroicon-m-arrow-left')
                    )
                    ->previousAction(
                        fn (FormAction $action) => $action
                            ->label('گام قبلی')
                            ->icon('heroicon-m-arrow-right')
                            ->color('gray')
                    )
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button type="submit" size="md" color="warning" icon="heroicon-m-truck">
                        ثبت قطعی بارگیری و صدور مانیفست راننده
                    </x-filament::button>
                BLADE))),
            ])
            ->statePath('data');
    }

    public function getAvailableOrdersList(Get $get): array
    {
        $operationType = $get('operation_type') ?? 'distribution';
        $driverId = $get('driver_id');
        $statusFilter = $get('status_filter') ?? 'auto';
        $ignoreAssigned = (bool) ($get('ignore_assigned_drivers') ?? true);

        $query = Order::query()->with(['customer', 'address', 'items.property', 'status']);

        // اعمال فیلتر وضعیت
        if ($statusFilter === 'all') {
            // هیچ فیلتر وضعیتی اعمال نشود
        } elseif (is_numeric($statusFilter)) {
            $query->where('status_id', (int) $statusFilter);
        } else {
            // حالت اتوماتیک
            if ($operationType === 'distribution') {
                $targetNames = ['ready_for_delivery', 'ready_for_deliver', 'ready_for_delivery_to_customer'];
            } else {
                $targetNames = ['reserved', 'in_collective_list', 'in_waiting_list', 'revisiting_driver'];
            }
            $targetStatusIds = OrderStatus::whereIn('name', $targetNames)->pluck('id')->toArray();

            if (!empty($targetStatusIds)) {
                $query->whereIn('status_id', $targetStatusIds);
            }
        }

        // فیلتر راننده
        if (!$ignoreAssigned && $driverId) {
            $query->where(function ($q) use ($driverId) {
                $q->whereNull('driver_id')->orWhere('driver_id', $driverId);
            });
        }

        return $query->latest()->take(50)->get()->mapWithKeys(function (Order $order) {
            $customerName = $order->customer?->name ?? 'بدون نام';
            $statusLabel = $order->status?->label ? " [{$order->status->label}]" : '';
            $zone = $order->address?->municipality_zone ? " (منطقه {$order->address->municipality_zone})" : '';
            return [$order->id => "سفارش #{$order->id} - {$customerName}{$statusLabel}{$zone}"];
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
                return ($item->property?->fullTitle ?? 'فرش') . " ({$item->quantity} عدد)";
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
            Notification::make()->title('خطا')->body('لطفاً حداقل یک سفارش و یک راننده را مشخص کنید.')->danger()->send();
            return;
        }

        $targetStatusName = $operationType === 'distribution'
            ? OrderStatusEnum::IN_DISTRIBUTION_LIST->value
            : OrderStatusEnum::IN_COLLECTIVE_LIST->value;

        $targetStatusId = OrderStatus::where('name', $targetStatusName)->value('id')
            ?? OrderStatus::first()->id;

        $driver = Driver::find($driverId);

        DB::transaction(function () use ($orderIds, $driverId, $driver, $targetStatusId, $shift, $targetStatusName) {
            $orders = Order::whereIn('id', $orderIds)->get();

            LogBatch::startBatch();

            foreach ($orders as $order) {
                $oldStatusId = $order->status_id;
                $oldDriverId = $order->driver_id;

                // ذخیره بدون تریگر لاگ تکراری
                $order->status_id = $targetStatusId;
                $order->driver_id = $driverId;
                $order->time_apply_status = Carbon::now();
                $order->saveQuietly();

                // ثبت دقیقاً یک لاگ معنادار
                activity('order')
                    ->causedBy(auth()->user())
                    ->performedOn($order)
                    ->withProperties([
                        'old' => [
                            'status_id' => $oldStatusId,
                            'driver_id' => $oldDriverId,
                        ],
                        'attributes' => [
                            'status_id' => $targetStatusId,
                            'driver_id' => $driverId,
                            'shift'     => $shift,
                        ],
                    ])
                    ->log("تحویل به راننده ({$driver?->name})");
            }

            LogBatch::endBatch();

            // بروزرسانی روت بهینه نشان برای راننده
            if ($driver) {
                app(OptimizedRoute::class)->calculateRoute([$driverId]);
            }

            event(new BulkOrderUpdated($orders, $targetStatusId));
        });

        Notification::make()
            ->title('عملیات بارگیری با موفقیت انجام شد')
            ->body(count($orderIds) . ' سفارش به مانیفست راننده اضافه شد.')
            ->success()
            ->send();

        $this->form->fill([
            'operation_type' => $operationType,
            'driver_id'      => null,
            'orders'         => [],
            'shift'          => $shift,
            'status_filter'  => 'auto',
            'ignore_assigned_drivers' => true,
        ]);
    }
}
