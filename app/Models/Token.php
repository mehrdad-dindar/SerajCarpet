<?php

namespace App\Models;

use App\Enums\SmsPattern;
use App\Traits\Sms;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\warning;

class Token extends Model
{
    use Sms;
    const EXPIRATION_TIME = 2; // minutes
    protected $fillable = [
        'code',
        'customer_id',
        'used'
    ];
    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['code'])) {
            $attributes['code'] = $this->generateCode();
        }
        parent::__construct($attributes);
    }
    /**
     * Generate a six digits code
     *
     * @param int $codeLength
     * @return string
     */
    public function generateCode(int $codeLength = 4): string
    {
        $max = pow(10, $codeLength);
        $min = $max / 10 - 1;
        return mt_rand($min, $max);
    }
    /**
     * User tokens relation
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    /**
     * True if the token is not used nor expired
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }
    /**
     * Is the current token used
     *
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->used;
    }
    /**
     * Is the current token expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->created_at->diffInMinutes(Carbon::now()) > static::EXPIRATION_TIME;
    }

    /**
     * @throws Exception
     */
    public function sendCode(): bool
    {
        if (! $this->customer) {
            throw new Exception("No user attached to this token.");
        }
        if (! $this->code) {
            $this->code = $this->generateCode();
        }
        try {
            if (app()->isProduction()) {
                $this->sendPattern($this->customer->phone, SmsPattern::LOGIN, [strval($this->code)]);
            } else {
                session()->put('code', strval($this->code));
            }
        } catch (Exception $ex) {
            // Log the exception with detailed information
            Log::warning('Failed to send SMS', [
                'exception' => $ex,
                'customer' => $this->customer,
                'phone' => $this->customer->phone ?? 'N/A',
                'code' => $this->code,
                'decryptedCode' => $decryptedCode ?? 'N/A',
            ]);
            return false; // Unable to send SMS
        }
        return true;
    }
}
