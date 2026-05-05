<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'is_featured',
        'service_category_id'
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
}


