<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Quote extends Model
{
    protected $fillable = [
        'service_id',
        'user_id',
        'name',
        'email',
        'phone',
        'details',
        'status' // pending, responded, closed
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

