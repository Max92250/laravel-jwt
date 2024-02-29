<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'identifier', 'status','created_by', 'updated_by'];

    protected $attributes = [
        'status' => 'active', // Default status
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function member()
    {
        return $this->hasOne(Member::class,'customer_id');
    }
    public function category()
    {
        return $this->hasMany(Category::class,'customer_id','id');
    }
    public function credit()
    {
        return $this->hasOne(Credit::class, 'customer_id', 'id');
    }
  
}
