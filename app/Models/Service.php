<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'features',
        'icon',
        'image',
        'is_featured',
        'service_category_id'
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
}


