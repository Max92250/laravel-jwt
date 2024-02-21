<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description' ,'created_by', 'updated_by','customer_id'];

  
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}
