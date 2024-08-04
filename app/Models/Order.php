<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function addresses(): BelongsTo
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
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => verta($value)->format('d F Y - H:i'),
        );
    }
    protected function reservedFor(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => !is_null($value) ? verta($value)->format('d F Y - H:i') : '',
        );
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => explode(',', $value),
            set: fn ($value) => implode(',', $value),
        );
    }

    public function status()
    {
        return OrderStatus::make($this->status);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class,"driver_id");
    }
}
