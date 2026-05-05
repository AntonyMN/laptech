<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = ['name', 'slug'];
}
