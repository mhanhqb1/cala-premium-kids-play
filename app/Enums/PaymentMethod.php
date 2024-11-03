<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case CASH = 0;
    case BANK_TRANSFER = 1;

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
        };
    }
}
