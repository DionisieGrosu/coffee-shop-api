<?php

namespace App\Enums;

enum Device: string
{
    case ANDROID = 'android';
    case IOS = 'ios';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
