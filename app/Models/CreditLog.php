<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'initial_amount',
        'Added_amount',
        'final_amount',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }





}
