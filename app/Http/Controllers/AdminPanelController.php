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
        $this->middleware('jwt.verify', ['except' => ['index', 'login']]);
        $this->middleware('role:admin', ['except' => ['index', 'login']]);
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
                return 'Email atau password salah';
            }

            if ($result->data->status->role !== 'admin') {
                return 'Akses ditolak. Hanya admin yang diizinkan';
            }

            // Set token in session
            session(['token' => $result->token]);
            session(['user' => (array)$result->data]);

            // Set token in cookie for subsequent requests
            $cookie = cookie('token', $result->token, 60); // 60 minutes

            return redirect()->route('admin.page')->withCookie($cookie);
        } catch (\Exception $e) {
            return 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}