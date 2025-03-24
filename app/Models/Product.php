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

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/photos/' . $this->photo) : asset('images/default.jpg');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
