<?php

namespace App\Enums;

enum Order: string
{
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function to_array(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->value;
        }

        return $array;
    }
}
