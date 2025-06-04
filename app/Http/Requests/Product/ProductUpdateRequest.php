<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        Log::info('raw data :', [
            $this->all()
        ]);
        return [
            'name' => 'sometimes|string|min:3|max:100',
            'description' => 'sometimes|string|min:10|max:500',
            'price' => 'sometimes|integer|min:10000',
            'category' => 'sometimes|string|in:Kamera,Elektronik,Outdoor,Kendaraan,Furnitur,Lainnya',
            'year' => 'sometimes|string|max:4',
            'images' => 'sometimes|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'address_text' => 'sometimes|string|min:10|max:200',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'additional_note' => 'sometimes|string|max:300',
        ];
    }
}
