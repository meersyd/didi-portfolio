<?php

namespace App\Support;

use Carbon\Carbon;

class Presence
{
    public static function online(): bool
    {
        $mode = config('portfolio.presence.mode', 'schedule');

        return match ($mode) {
            'online' => true,
            'offline' => false,
            default => static::withinSchedule(),
        };
    }

    public static function label(): string
    {
        return static::online() ? 'Online' : 'Offline';
    }

    protected static function withinSchedule(): bool
    {
        $timezone = config('portfolio.presence.timezone', 'Asia/Kuala_Lumpur');
        $now = Carbon::now($timezone);
        $from = Carbon::parse((string) config('portfolio.presence.online_from', '10:00'), $timezone);
        $until = Carbon::parse((string) config('portfolio.presence.online_until', '22:00'), $timezone);

        if ($from->equalTo($until)) {
            return true;
        }

        if ($from->lessThan($until)) {
            return $now->greaterThanOrEqualTo($from) && $now->lessThan($until);
        }

        return $now->greaterThanOrEqualTo($from) || $now->lessThan($until);
    }
}
