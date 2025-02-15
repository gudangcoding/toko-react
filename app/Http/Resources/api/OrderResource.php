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
            'shipping_id' => $this->shipping_id,
            'payment_id' => $this->payment_id,
            'orderItems' => OrderItemCollection::make($this->whenLoaded('orderItems')),
            'payment' => PaymentResource::make($this->whenLoaded('payment')),
        ];
    }
}
