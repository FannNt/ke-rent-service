<?php

namespace App\Http\Request\Payment;

use App\Classes\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => 'required|exists:transaction,id',
            'methods' => 'required|in:COD,Transfer,other',
            'status' => 'required|in:paid,not paid'
        ];
    }
}