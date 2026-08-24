<?php
namespace App\Services;

use App\Traits\Sms;
use MehrdadDindar\FilamentSurveyNotifier\Contracts\SmsSenderInterface;

class SmsSenderBridge implements SmsSenderInterface
{
    use Sms;

    public function sendPatternSms(string $mobile, string $patternCode, array $values): bool
    {
        return $this->sendPattern($mobile, $patternCode, $values);
    }
}
