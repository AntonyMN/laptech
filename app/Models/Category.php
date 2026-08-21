<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use \App\Models\Concerns\Auditable;

    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Products that are NOT archived — used for storefront queries.
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_archived', false);
    }
}

