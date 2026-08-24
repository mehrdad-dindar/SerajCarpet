<?php

namespace MehrdadDindar\FilamentPorsline\Enums;

enum NotificationType: int
{
    case EMAIL = 1;
    case SMS = 2;
    case WEBHOOK = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::EMAIL => 'ایمیل',
            self::SMS => 'پیامک',
            self::WEBHOOK => 'Webhook',
        };
    }
} 