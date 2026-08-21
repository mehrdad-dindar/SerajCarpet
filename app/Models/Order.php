<?php

namespace App\Models;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Events\OrderLogCreated;
use App\Services\CarpetPricingService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;

    public bool $updateDirection = true;
    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function (self $order) {
            $activity = Activity::forSubject($order)->latest()->first();
            if ($activity) {
                event(new OrderLogCreated($activity, $order->updateDirection));
            }
        });
    }

    public function setUpdateDirection(bool $value): void
    {
        $this->updateDirection = $value;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->nonCustom();
    }

    public function otherItems(): HasMany
    {
        return $this->hasMany(OrderItem::class)->custom();
    }

    public function total($items): int
    {
        return 200;
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function getStatusLabel(): ?string
    {
        return $this->status->label;
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Calculate order total using the dedicated CarpetPricingService.
     */
    public function recalculateTotals(): self
    {
        return app(CarpetPricingService::class)->syncAndSaveOrderTotals($this);
    }

    public function updateOrderStatus(string $statusName, $applyTime = null): void
    {
        $statusId = OrderStatus::getIdByName($statusName);
        if ($statusId) {
            $this->status_id = $statusId;
            if ($applyTime) {
                $this->time_apply_status = $applyTime;
            }
            $this->save();
        }
    }

    public function getStatusColor(): string
    {
        return OrderStatusEnum::tryFrom($this->status?->name ?? '')?->getColor() ?? 'gray';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status_id',
                'time_apply_status',
                'address_id',
                'driver_id',
                'collected_at',
                'sent_to_factory_at',
                'total',
            ])
            ->useLogName('order')
            ->logOnlyDirty();
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value ? verta($value)->format('d F Y - H:i') : null,
        );
    }

    protected function collectedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value ? verta($value)->format('d F Y - H:i') : null,
        );
    }

    public function getAllItemsAttribute()
    {
        return $this->items->merge($this->otherItems);
    }
}
