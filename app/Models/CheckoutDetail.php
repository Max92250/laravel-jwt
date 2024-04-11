<?php
// app/Models/CheckoutDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'payment_method',
        'card_number',
        'cart_key',
        'expiration_date',
        'cvv',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
