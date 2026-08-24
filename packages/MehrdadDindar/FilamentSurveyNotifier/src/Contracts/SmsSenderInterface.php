<?php

namespace MehrdadDindar\FilamentSurveyNotifier\Contracts;

interface SmsSenderInterface
{
    public function sendPatternSms(string $mobile, string $patternCode, array $values): bool;
}
