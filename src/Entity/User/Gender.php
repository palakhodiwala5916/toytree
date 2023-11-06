<?php

namespace App\Entity\User;

enum Gender : string
{
    case Male  = 'male';
    case Female = 'female';
    case Other = 'other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
