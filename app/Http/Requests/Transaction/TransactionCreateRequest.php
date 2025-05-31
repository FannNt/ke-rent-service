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
            'payment.methods' => 'required|in:COD,Transfer,Other',
            'payment.status' => 'sometimes|in:paid,not paid'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::sendErrorResponse($validator->errors(), 400)
        );
    }
}
