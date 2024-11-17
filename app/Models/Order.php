<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'discount',
        'discount_amount',
        'max_discount',
        'sub_total',
        'payment_method',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    protected static function boot()
    {
        parent::boot();

        // Sau khi đơn hàng được thanh toán
        static::created(function ($order) {
            if ($order->status === OrderStatus::COMPLETED) {
                $vipPercent = 1/100;//Todo: get percent by vip level
                $points = $order->total_amount*$vipPercent;

                // Cập nhật bảng loyalty_points
                \DB::table('loyalty_points')->insert([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'points'  => $points,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Cập nhật tổng điểm vào bảng users
                $order->user->increment('loyalty_points', $points);
                $order->user->increment('vip_exp', $points);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
