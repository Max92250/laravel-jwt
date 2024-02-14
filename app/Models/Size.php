<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'status'  ,'created_by',
    'updated_by','user_id'];

    public function subSizes()
    {
        return $this->hasMany(Size::class, 'parent_id');
    }
    public function item()
    {
        return $this->hasOne(Item::class, 'size_id');
    }

    
}
