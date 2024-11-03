<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'total_amount'];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
