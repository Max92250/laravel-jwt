<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
    ];


   

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

 public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
