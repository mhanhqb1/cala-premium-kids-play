<?php

namespace App\Enums;

enum ProductDiscountType: int
{
    case PERCENT = 0;
    case FIXED = 1;

    public function label(): string
    {
        return match($this) {
            self::PERCENT => 'Percent',
            self::FIXED => 'Fixed',
        };
    }
}
