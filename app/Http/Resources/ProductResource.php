<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource; 
use App\Http\Resources\ItemResource; 
use App\Http\Resources\CategoryResource; 

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'categories' => $this->categories->pluck('name')->toArray(),   
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
           
              
        ];
    }
}
