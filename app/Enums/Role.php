<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case USER = 'user';

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
