<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use \App\Models\Concerns\Auditable;

    protected $fillable = [
        'user_id',
        'items', // array of products with price/qty
        'total',
        'status', // pending, paid, shipped, delivered, cancelled
        'shipping_address'
    ];

    protected $casts = [
        'items' => 'array',
        'shipping_address' => 'array',
    ];
}

