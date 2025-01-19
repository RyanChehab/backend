<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class WriterMiddleware{

    public function handle(Request $request, Closure $next): Response{
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user->user_type !== 'writer'){
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Only writers are allowed.'
                ], 403);
            }
            return $next($request);
        }catch(TokenExpiredException $e){
        return response()->json([
            'success' => false,
            'message' => 'Invalid token. Please log in again.'
        ], 401);
    } catch (Exception $e) {
        // Handle missing token
        return response()->json([
            'success' => false,
            'message' => 'Token is required. Please log in.'
        ], 401);
    }

    return $next($request);
    }
}

