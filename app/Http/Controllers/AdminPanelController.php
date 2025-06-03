<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\ApiResponse;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\UserController;
use App\Http\Requests\User\UserLoginRequest;

class AdminPanelController extends Controller
{
    protected $UserController;

    public function __construct(UserController $UserController)
    {
        $this->UserController = $UserController;
    }

    public function index()
    {
        return view('login');
    }

    public function showHome()
    {
        return view('adminPage');
    }
    
    public function login(Request $request)
    {
        try {
            $loginRequest = app(UserLoginRequest::class);
            $loginRequest->initialize(
                $request->query(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all(),
                $request->getContent()
            );
            
            $response = $this->UserController->login($loginRequest);
            $result = $response->getData();

            if (!$result->success) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Oops...',
                    'text' => 'Email atau password salah'
                ])->header('Content-Type', 'application/json');
            }

            if ($result->data->status->role !== 'admin') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'text' => 'Hanya admin yang diizinkan'
                ])->header('Content-Type', 'application/json');
            }

            // Set token in session
            session(['token' => $result->token]);
            session(['user' => (array)$result->data]);

            return response()->json([
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Login berhasil',
                'redirect' => route('admin.page')
            ])->header('Content-Type', 'application/json');
        } catch(\Illuminate\Http\Exceptions\HttpResponseException $e){
            return response()->json([
                'type' => 'error',
                'title' => 'Oops...',
                'text' => 'Email atau password salah'
            ])->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Oops...',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->header('Content-Type', 'application/json');
        }
    }
}