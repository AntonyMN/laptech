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
        'status',
        'is_archived',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'is_featured' => 'boolean',
            'is_archived' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope: only active (non-archived) products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope: only archived products.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}
