<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use \App\Models\Concerns\Auditable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'specifications',
        'is_featured',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

