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
            'product_id'     => 'required|exists:products,id',
            'rental_start' => 'required|date',
            'rental_end' => 'required|date',
            'pickup_hour' => 'required|integer|min:0|max:23',
            'return_hour' => 'required|integer|min:0|max:23',
            'payment.methods' => 'required|in:COD,Transfer,Other',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::sendErrorResponse($validator->errors(), 400)
        );
    }
}
