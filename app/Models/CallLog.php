<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
//    use HasFactory;

    protected $fillable = [
        'customer_id',
        'caller_id',
        'extension',
        'did',
        'type',
        'duration',
        'recording_file',
        'uniqueid',
    ];

    /**
     * Get the customer associated with the call log.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
