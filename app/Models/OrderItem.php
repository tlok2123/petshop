<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];
    protected static function booted()
    {
        static::saving(function ($orderItem) {
            $orderItem->price = $orderItem->quantity * $orderItem->product->price;
        });

        static::saved(function ($orderItem) {
            $orderItem->order->updateTotalPrice();
        });

        static::deleted(function ($orderItem) {
            $orderItem->order->updateTotalPrice();
        });
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
