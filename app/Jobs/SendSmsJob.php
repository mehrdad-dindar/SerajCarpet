<?php

namespace App\Jobs;

use App\Traits\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Sms;
    protected $phone;
    protected $patternCode;
    protected $arr;

    /**
     * Create a new job instance.
     */
    public function __construct($phone, $patternCode, $arr)
    {
        $this->phone = $phone;
        $this->patternCode = $patternCode;
        $this->arr = $arr;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendPattern($this->phone,$this->patternCode,$this->arr);
    }
}
