<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource; 
use App\Http\Resources\ItemResource; 
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
          
        ];
    }
}
