<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
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
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'image' => $this->product->image->pluck('image'),
                    'price' => $this->product->price
                ];
            }),
            'rental_start' => $this->rental_start,
            'rental_end' => $this->rental_end,
            'status' => $this->status
        ];
    }
}
