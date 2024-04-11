<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_item_id',
        'sku',
        'product_id',
        'price',
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class,'product_item_id');
    }
}
