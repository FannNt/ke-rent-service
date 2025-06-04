<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'user' => $this->relationLoaded('user') ? new UserResource($this->user) : $this->user_id,
            'name' => $this->name,
            'price' => $this->price,
            'category' => $this->category,
            'description' => $this->description,
            'additional_note' => $this->additional_note,
            'address_text' => $this->address_text,
            'year' => $this->year,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_available' => $this->is_available ?? 1,
            'images' => $this->whenLoaded('image', function (){
                return $this->image->pluck('image');
            })

        ];
    }
}
