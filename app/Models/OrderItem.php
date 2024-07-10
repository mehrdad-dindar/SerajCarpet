<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;
    protected $guarded;

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
}
