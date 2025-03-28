<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // ✅ Import JWTSubject
use App\Notifications\CustomVerifyEmail;
use App\Models\Order;


class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'avatar',
    ];

    protected $attributes = [
        'role' => '2',
    ];

    public const ROLE_ADMIN = 1;
    public const ROLE_CUSTOMER = 2;

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }

    // ✅ Thêm hai method bắt buộc để dùng JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
