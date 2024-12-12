<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Controllers\JWTAuthController;

Route::post('/signup',[JWTAuthController::class, 'signup']);