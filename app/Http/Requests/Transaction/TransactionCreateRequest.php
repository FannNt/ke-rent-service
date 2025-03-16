<?php


namespace App\Http\Requests\Transaction;

use App\Classes\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_id' => 'required|exists:payment,id',
            'user_id' => 'required|exists:user,id',
            'total_price' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected,completed',
            'create_at' => 'required|date'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::sendResponse($validator->errors(), 400)
        );
    }

    protected function prepareForValidation()
    {
        $this->merge([
            // ki di sambung ng ndi fan aku rapaham, hehe
            'payment_id' => auth()->id(), 
            'user_id' => auth()->id()
        ]);
    }
}