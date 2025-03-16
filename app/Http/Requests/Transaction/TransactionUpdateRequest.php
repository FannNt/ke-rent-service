<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TransactionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules():array
    {
        // Log::info('raw data :', [
        //     $this->all()
        // ]);

        return [
            'status' => 'sometimes|in:pending,approved,rejected,completed'
        ];
    }
}