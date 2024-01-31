<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'size' => $this->size,
            'color' => $this->color,
            'sku' => $this->sku,
            'product_id' => $this->product_id
        
        ];
    }
}
