<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    protected $guarded;
    protected $casts = [
        'options' => 'array',
    ];

    protected $attributes = [
        'options' => null,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!isset($this->attributes['options'])) {
            $this->attributes['options'] = json_encode(
                Option::where('is_default', true)->pluck('id')->toArray()
            );
        }
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_custom', 1);
    }

    public function scopeNonCustom($query)
    {
        return $query->where('is_custom', 0);
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
