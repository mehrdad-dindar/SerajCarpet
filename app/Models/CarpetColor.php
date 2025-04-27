<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarpetColor extends Model
{
    protected $guarded;

    public function orderItem(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
