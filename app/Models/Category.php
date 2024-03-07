<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','created_by',
    'updated_by','customer_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
