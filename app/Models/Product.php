<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' đ';
    }
    public function getFormattedStockAttribute()
    {
        return number_format($this->stock);
    }
}
