<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_path',
        'product_id',
        'created_at',
    'updated_at',
    
 
    ];
    public function getImage1Attribute($value)
    {
        return asset('images/' . $value);
    }

    public function getImage2Attribute($value)
    {
        return asset('images/' . $value);
    }

    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
