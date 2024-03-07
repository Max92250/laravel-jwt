<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    /**
     * Get the customer that owns the credit.
     */
    public function customer()
    {
       
        return $this->belongsTo(Customer::class, 'id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class)
                    ->withPivot('amount')
                    ->withTimestamps();
    }

}
