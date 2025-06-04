<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => $this->data['type'],
            'message' => $this->data['message'],
            'transaction' => [
                'id' => $this->transaction->id,
                'rental_start' => $this->transaction->rental_start,
                'rental_end' => $this->transaction->rental_end,
                'product' =>  [
                    'id' => $this->transaction->product->id,
                    'name' => $this->transaction->product->name,
                    'price' => $this->transaction->product->price,
                    'image' => $this->transaction->product->image->pluck('image'),
                    'user' => new UserResource($this->transaction->product->user)
                ],
            ]
        ];
    }
}
