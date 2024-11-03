<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'amount', 'payment_date', 'payment_method', 'status'];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
        'status' => PaymentStatus::class,
    ];
}
