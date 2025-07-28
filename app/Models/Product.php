<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'quantity_in_stock',
        'price_per_item',
    ];
    public function getTotalValueNumberAttribute()
    {
        return $this->quantity_in_stock * $this->price_per_item;


    }
}
