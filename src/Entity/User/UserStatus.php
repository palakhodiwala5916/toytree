<?php

namespace App\Entity\User;

enum UserStatus : string
{
    case Active = 'active';
    case DeActive = 'de_active';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
