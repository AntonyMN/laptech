<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Order extends Model
{
    protected $fillable = [
        'user_id',
        'items', // array of products with price/qty
        'total',
        'status', // pending, paid, shipped, delivered, cancelled
        'shipping_address'
    ];
}

