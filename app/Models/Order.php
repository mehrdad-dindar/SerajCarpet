<?php

namespace App\Models;

use App\Events\BulkOrderUpdated;
use App\Events\OrderLogCreated;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static bool $isBulkUpdate = false;
    protected $guarded;

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            $activity = Activity::forSubject($order)->latest()->first();
            event(new OrderLogCreated($activity));
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->nonCustom();
    }

    public function other_items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->custom();
    }

    public function total($items): int
    {
        return 200;
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, "status_id");
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /*protected function reservedFor(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => !is_null($value) ? verta($value)->format('d F Y - H:i') : '',
        );
    }*/

    public function getStatusLabel(): ?string
    {
        return OrderStatus::from($this->status)->getLabel();
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            OrderStatus::RESERVED->value => 'bg-blue-500',
            OrderStatus::IN_WAITING_LIST->value => 'bg-yellow-500',
            OrderStatus::CARPETS_RECEIVED->value => 'bg-teal-500',
            OrderStatus::PRE_WASH_REPAIR_SERVICE->value => 'bg-orange-500',
            OrderStatus::SENT_TO_FACTORY_FOR_WASHING->value => 'bg-green-500',
            OrderStatus::POST_WASH_REPAIR_SERVICE->value => 'bg-red-500',
            OrderStatus::READY_FOR_DELIVERY_TO_CUSTOMER->value => 'bg-purple-500',
            OrderStatus::DELIVERED_AND_PAID->value => 'bg-green-700',
        };
    }

    public function getOptionModelsAttribute()
    {
        $optionIds = $this->options ?? [];
        return Option::whereIn('id', $optionIds)->get();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return __('order.' . $eventName);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status_id', 'time_apply_status', 'address_id', 'driver_id'])
            ->useLogName('order')
            ->logOnlyDirty();
    }

    public function updateOrderStatus(string $CARPETS_RECEIVED): void
    {
        $this->status_id = (OrderStatus::firstWhere('name', $CARPETS_RECEIVED))->id;
        $this->save();
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => verta($value)->format('d F Y - H:i'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => verta($value)->format('d F Y - H:i'),
        );
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
