<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use \App\Models\Concerns\Auditable;

    protected $fillable = ['name', 'slug'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
