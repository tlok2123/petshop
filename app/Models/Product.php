<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'photo',
    ];

    public function getPhotoAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/default.jpg');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
