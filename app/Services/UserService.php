<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Models\User;
use App\Interface\User\UserRepositoryInterface;
use Aws\Textract\Exception\TextractException;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;
    protected $cloudinaryService;
    protected $textractService;

    public function __construct(UserRepositoryInterface $userRepository, CloudinaryService $cloudinaryService, TextractService $textractService)
    {
        $this->userRepository = $userRepository;
        $this->cloudinaryService = $cloudinaryService;
        $this->textractService = $textractService;
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
        }catch (Exception $e){
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

    public function delete(Request $request)
    {
        try {
            $token =  $request->bearerToken();
            JWTAuth::invalidate($token);
            return true;
        }catch (\Mockery\Exception $e){
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

    public function loginWithNumber(array $data)
    {
        $user = $this->userRepository->findByNumber($data['phone_number']);

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

    public function uploadKtp($image)
    {
        try {
            $scan = $this->textractService->uploadKtp($image);
            $scanResult = $scan['scan_data'];
            $url = $scan['s3_path'];
            //check nik already use or not
            $used = $this->userRepository->findNik($scanResult['nik']);
            // Create KTP record
            if ($used) {
                $this->textractService->removeKtp($url);
                throw new HttpResponseException(
                    ApiResponse::sendErrorResponse('KTP not available',409)
                );
            }
            $repo = $this->userRepository->createKtp($scanResult, $url);

            if (!$repo) {
                $this->textractService->removeKtp($url);
                throw new HttpResponseException(
                    ApiResponse::sendErrorResponse('Failed to save KTP data')
                );
            }

            return $scanResult;
        } catch (HttpResponseException $e) {
            // Re-throw HttpResponseException
            throw $e;
        } catch (TextractException $e) {
            Log::error('Textract Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Failed to scan KTP: ' . $e->getMessage())
            );
        } catch (Exception $e) {
            Log::error('General Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('An error occurred while processing KTP')
            );
        }
    }
}
