<?php

namespace App\Http\Middleware;

use App\Classes\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMIddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$roles): Response
    {
        $user = auth()->user();

        if (!$user || !in_array($user->status->role,$roles)) {
            return ApiResponse::sendErrorResponse('Access Denied',403);
        }
        return $next($request);
    }
}
