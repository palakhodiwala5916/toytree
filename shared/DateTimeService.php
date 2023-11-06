<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

class DateTimeService
{

    public static function now(): DateTime
    {
        return new DateTime();
    }

    public static function convertUtcToUserTz(DateTime $date, string $timezone): DateTime
    {
        return $date->setTimeZone(new \DateTimeZone($timezone));
    }

    public static function time(): int
    {
        return self::now()->getTimestamp();
    }

    public static function format(string $format): string
    {
        return self::now()->format($format);
    }

    public static function modify(string $modifier): DateTime
    {
        return self::now()->modify($modifier);
    }

}