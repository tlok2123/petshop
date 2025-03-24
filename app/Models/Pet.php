<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'age',
        'health_status',
        'boarding_expiry',
    ];

    protected function casts(): array
    {
        return [
            'boarding_expiry' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isDog(): bool
    {
        return $this->species === 1;
    }

    public function isCat(): bool
    {
        return $this->species === 2;
    }
}
