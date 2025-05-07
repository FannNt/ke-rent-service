<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\PaymentCreateRequest;
use App\Http\Requests\Payment\PaymentUpdateRequest;
use App\Services\PaymentServices;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentServices $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function create(PaymentCreateRequest $request): JsonResponse
    {
        $payment = $this->paymentService->create($request->validated());
        return response()->json($payment, 201);
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
