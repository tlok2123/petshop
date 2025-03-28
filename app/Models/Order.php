<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];

    // Define status codes
    const STATUS_PROCESSING = 1;  // Đang xử lý
    const STATUS_COMPLETED = 2;   // Đã hoàn thành
    const STATUS_CANCELLED = 3;   // Đã hủy

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateTotalPrice()
    {
        $this->total_price = $this->items()->sum('price'); // Chỉ lấy tổng cột price
        $this->save();
    }

    // Optionally, add a method to get the status name
    public function getStatusName()
    {
        $statuses = [
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Đã hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];

        return $statuses[$this->status] ?? 'Không xác định';
    }
}
