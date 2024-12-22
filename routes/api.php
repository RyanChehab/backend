<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\PopulateBooksController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
Route::post('/signup',[JWTAuthController::class, 'signup']);

Route::post('/login',[JWTAuthController::class, 'login']);

Route::post('/reset', [JWTAuthController::class, 'resetPassword']);

Route::post('/populate', [PopulateBooksController::class, 'populate']);

// Blocking user
Route::middleware(AdminMiddleware::class)->group(function(){
    Route::post('/block_user',[JWTAuthController::class, 'block_user']);
});

// Deleting user and logingout
Route::middleware(JWTMiddleware::class)->group(function(){
    Route::post('/logout',[JWTAuthController::class, 'logout']);
    Route::post('delete_user',[JWTAuthController::class, 'delete_user']);
});

// Route::get('/test-email', function () {
//     Mail::to('test@example.com')->send(new \App\Mail\ResetPasswordMail('http://example.com/reset-password?token=12345'));
//     return 'Email sent!';
// });