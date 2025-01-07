<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class Writer{

    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user->user_type !== 'writer'){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Only writers are allowed.'
                ], 403);
            }
            return $next($request);
        }catch(TokenExpiredException $e)
       
    }
}
