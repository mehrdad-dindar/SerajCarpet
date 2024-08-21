<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Sms extends Model
{
    protected $guarded;

    public function smsable(): MorphTo
    {
        return $this->morphTo();
    }
}
