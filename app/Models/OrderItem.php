<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrderItem extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = [];
    protected $casts = [
        'options' => 'array',
    ];

    protected $attributes = [
        'options' => null,
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('carpet_images')
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);

        $this->addMediaCollection('attachments')
            ->useDisk('media')
            ->acceptsMimeTypes(['video/*', 'audio/*']);
    }

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

    public function color(): BelongsTo
    {
        return $this->belongsTo(CarpetColor::class, 'carpet_color_id');
    }
}
