<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes,HasFactory;
    protected $guarded;

    // order relationship
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
