<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'images',
        'specifications',
        'is_featured'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

