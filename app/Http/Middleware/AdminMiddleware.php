<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware{
    
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();

            if($user->user_type !== 'admin'){
                return response()->json([
                    'message'=>'Access denied. Admins only'
                ],403);
            }
        }catch
        return $next($request);
    }
}
