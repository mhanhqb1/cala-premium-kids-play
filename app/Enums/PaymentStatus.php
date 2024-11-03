<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case FAILED = -1;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
        };
    }
}
