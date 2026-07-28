<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use \App\Models\Concerns\Auditable;

    protected $fillable = ['name', 'slug', 'description'];

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }
}
