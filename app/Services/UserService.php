<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Models\User;
use App\Interface\User\UserRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;
    protected $cloudinaryService;

    public function __construct(UserRepositoryInterface $userRepository, CloudinaryService $cloudinaryService)
    {
        $this->userRepository = $userRepository;
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index()
    {
        return $this->userRepository->all();

    }
    public function create(array $data)
    {
        try {
            if (isset($data['profile_image'])){
                $image = $this->cloudinaryService->uploadProduct($data['profile_image']);
                $data['profile_image'] = $image['url'];
            } else{
                $data['profile_image'] = '';
            }
            $userStatus = $this->userRepository->createStatus();
            $data['user_status_id'] = $userStatus->id;
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);
            $token = JWTAuth::fromUser($user);
            return ApiResponse::sendResponseWithToken($user,$token,'',201);
        }catch (e){
            return ApiResponse::sendErrorResponse('failed create account');
        }
    }

    public function update(User $user, array $data)
    {

            if (!Hash::check($data['password'], $user->getAuthPassword())) {
                throw new HttpResponseException(
                    ApiResponse::sendErrorResponse('credential not match', 400)
                );
            }
            $status =  $user->update($data);
            return [
                'user' => $user,
                'status' => $status
            ];

    }

    public function delete(\Illuminate\Http\Request $request)
    {
        try {
            $token =  $request->bearerToken();
            JWTAuth::invalidate($token);
            return true;
        }catch (e){
            return false;
        }
    }

    public function findById($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new HttpResponseException(
                ApiResponse::sendResponse('','Invalid Credential', 401)
            );
        }
    }

    public function login(array $data)
    {
           $user = $this->userRepository->findByEmail($data['email']);

           if (!$user || !Hash::check($data['password'], $user->password)){
                throw new HttpResponseException(
                    ApiResponse::sendResponse('', 'Invalid credentials', 401)
                );
           }
            $token = JWTAuth::fromUser($user);
           return ApiResponse::sendResponseWithToken($user,$token, '');
    }

    public function me()
    {
        return auth()->user();
    }

}
