<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            
            $user = JWTAuth::parseToken()->authenticate();
        }catch(JWTException $error){

            return response()->json(['error' => 'token is invalid'],401);
        }
        
        return $next($request);
    }
}
