<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'customer_id',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
