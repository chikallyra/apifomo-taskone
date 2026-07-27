<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('name', 'price', 'inventory')]

class Product extends Model
{
    public function order_item(){
        return $this->hasMany(OrderItem::class);
    }
}
