<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('customer_name', 'status')]

class Order extends Model
{
    public function order_item(){
        return $this->hasMany(OrderItem::class);
    }
}
