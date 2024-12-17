<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';

    public static function isValidStatus(string $status): bool
    {
        return in_array($status, array_column(self::cases(), 'value'));
    }
}
