<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description' ,'created_at',
    'updated_at',];

  


    public function items()
    {
        return $this->hasMany(Item::class);
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
