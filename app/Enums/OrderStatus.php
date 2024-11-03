<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case CANCELLED = -1;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
