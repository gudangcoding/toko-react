<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Categories_id' => $this->Categories_id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->price,
            'stock' => $this->stock,
            'categories_id' => $this->categories_id,
            'categories' => CategoriesResource::make($this->whenLoaded('categories')),
            'orderItems' => OrderItemCollection::make($this->whenLoaded('orderItems')),
        ];
    }
}
