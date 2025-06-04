<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMIddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Cek apakah request dari API atau Web
            if ($request->is('api/*')) {
                // Untuk API, hanya terima token dari header
                $user = JWTAuth::parseToken()->authenticate();
            } else {
                // Untuk Web, cek session dulu
                if (session()->has('token')) {
                    $request->headers->set('Authorization', 'Bearer ' . session('token'));
                    $user = JWTAuth::parseToken()->authenticate();
                } else {
                    return redirect()->route('admin.login');
                }
            }
        } catch (Exception $e) {
            if ($request->is('api/*')) {
                // Untuk API, return JSON response
                if ($e instanceof TokenInvalidException){
                    return response()->json(['status' => 'Token is Invalid'], 401);
                }else if ($e instanceof TokenExpiredException){
                    return response()->json(['status' => 'Token is Expired'], 401);
                }else{
                    return response()->json(['status' => 'Authorization Token not found'], 401);
                }
            } else {
                // Untuk Web, redirect ke login
                return redirect()->route('admin.login');
            }
        }
        return $next($request);
    }
}
