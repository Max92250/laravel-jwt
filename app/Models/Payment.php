<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'payment_method',
        'card_number',
        'expiration_date',
        'cvv',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

}
