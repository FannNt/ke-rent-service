<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => $this->whenLoaded('product',function (){
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => $this->product->price,
                    'image' => $this->product->image->pluck('image'),
                    'address_text' => $this->product->address_text,
                    'latitude' => $this->product->latitude,
                    'longitude' => $this->product->longitude
                ];
            }),
            'user' => new UserResource($this->product->user),
            'total_price' => $this->total_price,
            'rental_start' => $this->rental_start,
            'rental_end' => $this->rental_end,
            'rent_day' => $this->rent_day,
            'status' => $this->status,
            'payment' => $this->whenLoaded('payment', function (){
                return [
                    'id' => $this->payment->id,
                    'method' => $this->payment->methods,
                    'status' => $this->payment->status
                ];
            }),
        ];
    }
}
