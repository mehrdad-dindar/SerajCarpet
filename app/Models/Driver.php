<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use SoftDeletes,HasFactory, Notifiable;
    protected $guarded;

    // order relationship
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Retrieve the tokens associated with this instance.
     *
     * @return MorphMany
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(Token::class, 'tokenable');
    }

    public function optimizedRoutes(): HasMany
    {
        return $this->hasMany(OptimizedRoute::class);
    }
    public static function getName($state): string
    {
        if ($state !== null) {
            return self::find($state)->name ?? "راننده #" . $state;
        } else {
            return 'N/A';
        }
    }
}
