<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SizeResource; 
class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'size_id' => new SizeResource($this->size),
            'color' => $this->color,
            'sku' => $this->sku,
            'product_id' => $this->product_id
        
        ];
    }
}
