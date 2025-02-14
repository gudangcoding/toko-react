<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total' => $this->total,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'shipping_status' => $this->shipping_status,
            'orderItems' => OrderItemCollection::make($this->whenLoaded('orderItems')),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
