<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['sku', 'price','quantity','size_id',  'color',  'product_id','created_at'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class,'size_id','id');
    }

   
}
