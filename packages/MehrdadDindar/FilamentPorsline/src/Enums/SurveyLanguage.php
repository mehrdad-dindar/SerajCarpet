<?php

namespace MehrdadDindar\FilamentPorsline\Enums;

enum SurveyLanguage: int
{
    case ENGLISH = 1;
    case PERSIAN = 2;
    case TURKISH = 3;
    case ARABIC = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::ENGLISH => 'English',
            self::PERSIAN => 'فارسی',
            self::TURKISH => 'Türkçe',
            self::ARABIC => 'العربية',
        };
    }
}
