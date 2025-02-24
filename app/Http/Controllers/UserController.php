<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\user\UserLoginRequest;
use App\Http\Requests\user\UserRegisterRequest;
use App\Http\Requests\user\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create(UserRegisterRequest $request)
    {

        $data = $this->userService->create($request->validated());
        return ApiResponse::sendResponse($data,'User Created',201);
    }

    public function login(UserLoginRequest $request)
    {
        $data = $this->userService->login($request->validated());
        if (!$data) {
            return ApiResponse::sendResponse('','Internal Server Error',500);
        }
        return $data;
    }

    public function me()
    {
        $user = $this->userService->me();

        return ApiResponse::sendResponse($user,'');
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $data = $this->userService->update($user,$request->validated());

        return ApiResponse::sendResponse($data['user'],$data['status']);
    }
}
