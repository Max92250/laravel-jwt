<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['sku', 'price', 'size', 'color',  'product_id','created_at',
    'updated_at',];

   

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
