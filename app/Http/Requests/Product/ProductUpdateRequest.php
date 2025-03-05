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
            'name' => 'sometimes|string|min:3|max:50',
            'price' => 'sometimes|integer|',
            'description' => 'sometimes|string|max:200',
            'image' => 'sometimes|image',
        ];
    }
}
