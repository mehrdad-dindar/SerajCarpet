<?php

namespace App\Livewire\Driver\Order;

use App\Events\OrderReceivedByDriver;
use App\Models\CarpetColor;
use App\Models\Comment;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Property;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('کارشناسی و تحویل فرش')]
class EditOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public Order $order;
    public ?array $data = [];

    public function mount(Order $order): void
    {
        $this->order = $order->load(['customer', 'address', 'items.media', 'otherItems']);

        $formData = $this->order->toArray();
        $formData['selected_options'] = $this->order->options ?? Option::where('is_default', true)->pluck('id')->toArray();

        $this->form->fill($formData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->order)
            ->schema([
                Wizard::make([
                    // مرحله ۱: تأیید مشتری و آدرس
                    Wizard\Step::make('مشتری و آدرس')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(3)->schema([
                                Placeholder::make('customer_name')
                                    ->label('نام مشتری')
                                    ->content(fn () => $this->order->customer?->name ?? 'بدون نام'),
                                Placeholder::make('customer_phone')
                                    ->label('شماره همراه')
                                    ->content(fn () => $this->order->customer?->phone),
                                Placeholder::make('full_address')
                                    ->label('آدرس کامل')
                                    ->content(fn () => $this->order->address?->getFullAddress() ?? 'فاقد آدرس')
                                    ->columnSpan(3),
                            ]),
                        ]),

                    // مرحله ۲: کارشناسی اقلام، متراژ دقیق، رنگ و عکس عیوب
                    Wizard\Step::make('کارشناسی و متراژ فرش‌ها')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            Repeater::make('items')
                                ->label('لیست فرش‌ها و اقلام تحویلی')
                                ->relationship('items')
                                ->defaultItems(1)
                                ->addActionLabel('+ افزودن فرش جدید')
                                ->reorderable(false)
                                ->schema([
                                    Grid::make(12)->schema([
                                        Select::make('property_id')
                                            ->label('نوع فرش و خدمت')
                                            ->relationship('property', 'name')
                                            ->getOptionLabelFromRecordUsing(fn (Property $p) => $p->fullTitle . ' (' . number_format($p->price) . ' ت)')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required()
                                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                                $prop = Property::find($state);
                                                if ($prop) {
                                                    $set('unit_price', $prop->price);
                                                    self::recalculateItemTotal($set, $get);
                                                }
                                            })
                                            ->columnSpan(4),

                                        Select::make('carpet_color_id')
                                            ->label('رنگ زمینه')
                                            ->options(CarpetColor::pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(2),

                                        Select::make('dimensions')
                                            ->label('متراژ استاندارد')
                                            ->options([
                                                4  => '۴ متری (۱.۵ × ۲.۲۵)',
                                                6  => '۶ متری (۲ × ۳)',
                                                9  => '۹ متری (۲.۵ × ۳.۵)',
                                                12 => '۱۲ متری (۳ × ۴)',
                                                24 => '۲۴ متری',
                                            ])
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateItemTotal($set, $get))
                                            ->columnSpan(2),

                                        TextInput::make('quantity')
                                            ->label('تعداد')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateItemTotal($set, $get))
                                            ->columnSpan(1)
                                            ->required(),

                                        Hidden::make('unit_price')->default(0),

                                        TextInput::make('sub_total')
                                            ->label('مبلغ قلم (تومان)')
                                            ->readOnly()
                                            ->dehydrated()
                                            ->columnSpan(3),
                                    ]),

                                    // بخش ثبت عیوب اولیه و عکس‌برداری در محل
                                    Section::make('وضعیت سلامت فرش و ثبت عیوب اولیه (قبل از بارگیری)')
                                        ->icon('heroicon-o-camera')
                                        ->collapsed()
                                        ->schema([
                                            CheckboxList::make('options.defects')
                                                ->label('عیوب ظاهری مشاهده شده توسط راننده:')
                                                ->options([
                                                    'tear'       => 'پارگی یا سوراخ‌شدگی',
                                                    'burn'       => 'سوختگی (اتوی داغ/ذغال)',
                                                    'decay'      => 'پوسیدگی ریشه یا چله',
                                                    'ink_stain'  => 'لکه جوهر یا رنگ‌دویدگی',
                                                    'oil_stain'  => 'لکه چربی یا چسب شدید',
                                                    'wear'       => 'ساییدگی خواب فرش',
                                                ])
                                                ->columns(3),

                                            SpatieMediaLibraryFileUpload::make('carpet_images')
                                                ->label('تصویر از عیوب یا کل فرش (جهت ضمیمه در پرونده)')
                                                ->collection('carpet_images')
                                                ->disk('media')
                                                ->multiple()
                                                ->maxFiles(3)
                                                ->image()
                                                ->imageResizeMode('cover')
                                                ->imageCropAspectRatio('16:9')
                                                ->imageResizeTargetWidth('1280')
                                                ->imageResizeTargetHeight('720')
                                                ->columnSpanFull(),
                                        ]),
                                ])
                                ->columnSpanFull(),
                        ]),

                    // مرحله ۳: ثبت نهایی و تحویل
                    Wizard\Step::make('تأیید و تحویل')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            Select::make('selected_options')
                                ->label('خدمات جانبی درخواستی')
                                ->multiple()
                                ->options(Option::pluck('name', 'id'))
                                ->preload()
                                ->columnSpanFull(),

                            Textarea::make('driver_comment')
                                ->label('توضیحات یا نکات راننده')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                ])
                    ->submitAction(new HtmlString(
                        '<button type="submit" class="w-full py-3.5 px-8 text-base font-black text-white bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 rounded-2xl shadow-xl shadow-green-500/30 transition transform active:scale-95 flex items-center justify-center gap-2">✓ دریافت فرش‌ها و ثبت در سیستم</button>'
                    )),
            ])
            ->statePath('data');
    }

    public static function recalculateItemTotal(Set $set, Get $get): void
    {
        $dims = (float) ($get('dimensions') ?: 1);
        $qty = (int) ($get('quantity') ?: 1);
        $unitPrice = (int) ($get('unit_price') ?: 0);

        $subTotal = $dims * $qty * $unitPrice;
        $set('sub_total', number_format($subTotal));
    }

    public function save()
    {
        $data = $this->form->getState();

        // ۱. محاسبه جمع کل سفارش
        $total = 0;
        foreach ($data['items'] ?? [] as $item) {
            $dims = (float) ($item['dimensions'] ?? 1);
            $qty = (int) ($item['quantity'] ?? 1);
            $unitPrice = (int) ($item['unit_price'] ?? 0);
            $total += $dims * $qty * $unitPrice;
        }

        // ۲. تغییر وضعیت به «تحویل گرفته شده توسط راننده»
        $receivedStatusId = OrderStatus::where('name', OrderStatus::CARPETS_RECEIVED)->value('id');

        $this->order->update([
            'status_id'    => $receivedStatusId,
            'total'        => $total,
            'sub_total'    => $total,
            'collected_at' => Carbon::now(),
            'options'      => $data['selected_options'] ?? [],
        ]);

        // ۳. ثبت توضیحات راننده
        if (!empty($data['driver_comment'])) {
            $this->order->comments()->create([
                'body'           => $data['driver_comment'],
                'commenter_type' => get_class(auth('driver')->user()),
                'commenter_id'   => auth('driver')->id(),
            ]);
        }

        // ۴. ارسال رویداد جهت پیامک اطلاع‌رسانی به مشتری
        event(new OrderReceivedByDriver($this->order));

        session()->flash('message', "سفارش #{$this->order->id} با موفقیت تحویل گرفته شد.");
        return redirect()->route('driver.orders');
    }

    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.order.edit-order');
    }
}
