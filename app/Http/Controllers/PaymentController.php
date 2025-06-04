<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Request\Payment\PaymentCreateRequest;
use App\Http\Request\Payment\PaymentUpdateRequest;
use Illuminate\Http\Request;
use App\Services\PaymentServices;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentServices $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay($transactionId): JsonResponse
    {
        $result = $this->paymentService->pay($transactionId);

        return ApiResponse::sendResponse([
            'token' => $result['token'],
            'order_id' => $result['order_id']
            ],'');
    }

    public function update(PaymentUpdateRequest $request, $id): JsonResponse
    {
        $payment = $this->paymentService->update($id, $request->validated());
        return response()->json($payment);
    }

    public function findById($id): JsonResponse
    {
        $payment = $this->paymentService->findById($id);
        return response()->json($payment);
    }
}
