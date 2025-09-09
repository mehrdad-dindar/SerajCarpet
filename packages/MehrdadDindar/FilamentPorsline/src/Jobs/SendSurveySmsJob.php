<?php

namespace MehrdadDindar\FilamentPorsline\Jobs;

use App\Traits\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MehrdadDindar\FilamentPorsline\Models\Survey;

class SendSurveySmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Sms;

    public function __construct(
        protected string $phone,
        protected Survey $survey,
        protected string $customerName = ''
    ) {
    }

    public function handle(): void
    {
        $patternCode = config('filament-porsline.sms.pattern_code', 250000);
        
        $message = config('filament-porsline.sms.message_template');
        $message = str_replace('{customer_name}', $this->customerName, $message);
        $message = str_replace('{survey_url}', $this->survey->survey_url, $message);

        $this->sendPattern($this->phone, $patternCode, [$message]);
    }
} 