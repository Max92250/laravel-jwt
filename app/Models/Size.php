<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'status'  ,'created_by',
    'updated_by','customer_id'];

    public function subSizes()
    {
        return $this->hasMany(Size::class, 'parent_id');
    }
    public function item()
    {
        return $this->hasOne(Item::class, 'size_id');
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
