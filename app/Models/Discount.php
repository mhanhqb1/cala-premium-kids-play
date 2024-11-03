<?php

namespace App\Models;

use App\Enums\ProductDiscountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'discount_type', 'discount_value', 'start_date', 'end_date'];

    protected $casts = [
        'discount_type' => ProductDiscountType::class,
    ];
}
