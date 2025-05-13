<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $guarded;
    const STATUS_SUCCESS = 2;
    const STATUS_PENDING = 1;
    const STATUS_FAILED = 0;

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function setInvoiceDetailsAttribute($value): void
    {
        $this->attributes['invoice_details'] = serialize($value);
    }

    public function getInvoiceDetailsAttribute()
    {
        return unserialize($this->attributes['invoice_details']);
    }

    public function setTransactionResultAttribute($value): void
    {
        $this->attributes['transaction_result'] = serialize($value);
    }

    public function getTransactionResultAttribute()
    {
        return unserialize($this->attributes['transaction_result']);
    }
}
