<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'identifier', 'status'];

    protected $attributes = [
        'status' => 'active', // Default status
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
