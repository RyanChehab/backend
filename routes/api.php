<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Controllers\JWTAuthController;

Route::post('/signup',[JWTAuthController::class, 'signup']);

Route::post('/login',[JWTAuthController::class, 'login']);

Route::middleware(JWTMiddleware::class)->group(function(){
    Route::post('/logout',[JWTAuthController::class, 'logout']);
    Route::post('delete_user',[JWTAuthController::class, 'delete_user']);
});

Route::post('/block_user',[JWTAuthController::class, 'block_user']);