<?php

namespace App\Livewire\Customer\Order;

use App\Enums\SmsPattern;
use App\Jobs\SendSmsJob;
use App\Models\Address;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Property;
use App\Settings\ShiftSettings;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("ثبت آنلاین سفارش قالیشویی")]
#[Layout('customer.layouts.app')]
class CreateOrderWizard extends Component
{
    use LivewireAlert;

    public int $currentStep = 1;

    // گام ۱: اقلام سفارش
    public array $orderItems = [];
    public array $selectedOptions = [];

    // گام ۲: آدرس
    public ?int $selectedAddressId = null;

    // گام ۳: زمان‌بندی و شیفت
    public ?string $reservationDate = null;
    public ?string $reservationTime = null;
    public ?string $comment = null;

    // داده‌های کمکی
    public Collection $availableProperties;
    public Collection $availableOptions;
    public array $availableDates = [];
    public array $availableShifts = [];

    public function mount(): void
    {
        $this->availableProperties = Property::with('serviceItem.service')->get();
        $this->availableOptions = Option::all();
        $this->selectedOptions = Option::where('is_default', true)->pluck('id')->toArray();

        // مقداردهی اولیه اولین قلم سفارش
        $this->addItem();

        // تنظیم آدرس پیش‌فرض مشتری
        $defaultAddress = auth('customer')->user()->addresses()->where('is_active', true)->first()
            ?? auth('customer')->user()->addresses()->latest()->first();
        $this->selectedAddressId = $defaultAddress?->id;

        // تولید تاریخ‌های انتخابی (۷ روز آینده به شمسی)
        $this->generateAvailableDates();
        $this->reservationDate = array_key_first($this->availableDates);
        $this->updateShifts();
    }

    public function addItem(): void
    {
        $firstProperty = $this->availableProperties->first();
        $this->orderItems[] = [
            'property_id' => $firstProperty?->id,
            'quantity'    => 1,
            'dimensions'  => !empty($firstProperty?->dimensions) ? $firstProperty->dimensions[0] : 1,
        ];
    }

    public function removeItem(int $index): void
    {
        if (count($this->orderItems) > 1) {
            unset($this->orderItems[$index]);
            $this->orderItems = array_values($this->orderItems);
        } else {
            $this->alert('warning', 'حداقل یک قلم سفارش باید ثبت شود.');
        }
    }

    public function generateAvailableDates(): void
    {
        $now = Verta::now();
        for ($i = 0; $i < 7; $i++) {
            $date = $now->copy()->addDays($i);
            $this->availableDates[$date->format('Y/m/d')] = $date->format('l (d F)');
        }
    }

    public function updatedReservationDate(): void
    {
        $this->updateShifts();
    }

    public function updateShifts(): void
    {
        if ($this->reservationDate) {
            $gregorianDate = Verta::parse($this->reservationDate)->toCarbon()->toDateString();
            $shifts = ShiftSettings::getDayShifts($gregorianDate);
            $this->availableShifts = [];

            foreach ($shifts as $groupName => $times) {
                foreach ($times as $startTime => $title) {
                    $this->availableShifts[$startTime] = "{$groupName}: {$title}";
                }
            }

            $this->reservationTime = array_key_first($this->availableShifts);
        }
    }

    public function calculateEstimatedTotal(): int
    {
        $total = 0;
        foreach ($this->orderItems as $item) {
            $property = $this->availableProperties->firstWhere('id', $item['property_id']);
            if ($property) {
                $dims = (int) ($item['dimensions'] ?? 1);
                $qty = (int) ($item['quantity'] ?? 1);
                $total += $dims * $qty * (int) $property->price;
            }
        }
        return $total;
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'orderItems' => 'required|array|min:1',
                'orderItems.*.property_id' => 'required|exists:properties,id',
                'orderItems.*.quantity' => 'required|integer|min:1',
            ]);
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'selectedAddressId' => 'required|exists:addresses,id',
            ], [
                'selectedAddressId.required' => 'لطفاً آدرس محل تحویل را انتخاب کنید یا آدرس جدید اضافه نمایید.',
            ]);
            $this->currentStep = 3;
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'reservationDate' => 'required|string',
                'reservationTime' => 'required|string',
            ]);
            $this->currentStep = 4;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submitOrder()
    {
        $customer = auth('customer')->user();

        // تبدیل تاریخ و ساعت رزرو
        $gregorianDate = Verta::parse($this->reservationDate)->toCarbon()->toDateString();
        $reservationDateTime = Carbon::parse("{$gregorianDate} {$this->reservationTime}");

        // وضعیت رزرو اولیه
        $statusId = OrderStatus::where('name', OrderStatus::RESERVED)->value('id') ?? 1;

        $estimatedTotal = $this->calculateEstimatedTotal();

        // ۱. ایجاد رکورد سفارش
        $order = Order::create([
            'customer_id'       => $customer->id,
            'address_id'        => $this->selectedAddressId,
            'status_id'         => $statusId,
            'time_apply_status' => $reservationDateTime,
            'total'             => $estimatedTotal,
            'sub_total'         => $estimatedTotal,
            'in_person_delivery'=> false,
        ]);

        // ۲. ایجاد آیتم‌های سفارش
        foreach ($this->orderItems as $item) {
            $property = $this->availableProperties->firstWhere('id', $item['property_id']);
            $dims = (int) ($item['dimensions'] ?? 1);
            $qty = (int) ($item['quantity'] ?? 1);
            $unitPrice = $property ? $property->price : 0;

            OrderItem::create([
                'order_id'    => $order->id,
                'property_id' => $item['property_id'],
                'dimensions'  => $dims,
                'quantity'    => $qty,
                'unit_price'  => (string) $unitPrice,
                'sub_total'   => (string) ($dims * $qty * $unitPrice),
                'options'     => $this->selectedOptions,
                'is_custom'   => 0,
            ]);
        }

        // ۳. ثبت توضیحات در صورت وجود
        if (!empty($this->comment)) {
            $order->comments()->create([
                'body'           => $this->comment,
                'commenter_type' => get_class($customer),
                'commenter_id'   => $customer->id,
            ]);
        }

        // ۴. ارسال پیامک تأیید ثبت سفارش به مشتری
        try {
            SendSmsJob::dispatch(
                $customer->phone,
                SmsPattern::ORDER_RECEIVED,
                [
                    $customer->name ?? 'مشتری گرامی',
                    (string) $order->id,
                    verta($order->time_apply_status)->format('Y/m/d H:i'),
                    number_format($order->total)
                ]
            );
        } catch (\Exception $e) {
            \Log::warning('Order SMS failed: ' . $e->getMessage());
        }

        $this->alert('success', 'سفارش شما با موفقیت ثبت شد و در صف مراجعه راننده قرار گرفت.');

        return redirect()->route('customer.panel.order.show', $order);
    }

    public function render()
    {
        return view('livewire.customer.order.create-order-wizard', [
            'estimatedTotal' => $this->calculateEstimatedTotal(),
            'addresses'      => auth('customer')->user()->addresses()->get(),
        ]);
    }
}
