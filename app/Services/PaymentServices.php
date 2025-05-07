<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Models\Payment;
use App\Interface\Payment\PaymentRepositoryInterface;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Repositories\Payment\PaymentRepositories;

class PaymentServices implements ServiceInterface
{
    protected $PaymentRepo;

    public function __construct(PaymentRepositories $PaymentRepo)
    {
        $this->PaymentRepo = $PaymentRepo;
    }

    public function create(array $data)
    {
        return $this->PaymentRepo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->PaymentRepo->update($id, $data);
    }

    public function findByID($id)
    {
        return $this->PaymentRepo->findById($id);
    }

    public function index()
    {

    }

    public function getByUserId($userId)
    {

    }

    public function delete($id)
    {

    }
}