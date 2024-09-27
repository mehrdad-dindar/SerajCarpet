<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $guarded;
    public $timestamps = false;

    protected $casts = [
        'dimensions' => 'array',
    ];

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'parent_id');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    protected function fullTitle(): Attribute
    {
        $parent = null;
        $title = $this->serviceItem->service->name . ' ' . $this->serviceItem->name . ' ';
        if (!is_null($this->parent_id)) {
            $parent = $this->parent;
        }
        if (!is_null($parent)) {
            $title .= $parent->name . ' ';
        }
        $title .= $this->name;

        return Attribute::make(
            get: fn () => $title,
        );
    }

    protected function helperText(): Attribute
    {
        $title =  ' هر ' . __($this->unit) . ' ' . number_format($this->price) . ' تومان';
        return Attribute::make(
            get: fn () => $title,
        );
    }
}
