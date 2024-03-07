<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'member_id',
        'shipment_id',
        'payment_id',
        'quantity',
        'total',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function payment()
    {
        return $this->belongsTo(Credit::class,'shipment_id','id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('item_id','quantity');
    }

  
 
}
