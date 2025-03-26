<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_id',
        'date',
        'status',
        'note',
        'total_price',
    ];

    public const STATUS_PENDING = 1;
    public const STATUS_CONTACTED = 2;
    public const STATUS_CONFIRMED = 3;
    public const STATUS_COMPLETED = 4;

    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với Pet
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Quan hệ với AppointmentService
     */
    public function services()
    {
        return $this->hasMany(AppointmentService::class);
    }

    /**
     * Cập nhật tổng tiền của đơn dịch vụ
     */
    public function updateTotalPrice()
    {
        $this->total_price = $this->services()->sum(\DB::raw('quantity * price'));
        $this->save();
    }
}
