<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\GetBooksController;
use App\Http\Controllers\ProfilePicController;
use App\Http\Controllers\PopulateBooksController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

//Auth
Route::group(['prefix' => 'auth'],function(){
    Route::post('/signup',[JWTAuthController::class, 'signup']);
    Route::post('/login',[JWTAuthController::class, 'login']);
    Route::post('/logout',[JWTAuthController::class, 'logout']);
});

// Blocking user
Route::middleware(AdminMiddleware::class)->group(function(){
    Route::post('/block_user',[JWTAuthController::class, 'block_user']);
});

// Deleting user and logingout
Route::middleware(JWTMiddleware::class)->group(function(){
    Route::post('delete_user',[JWTAuthController::class, 'delete_user']);
});

Route::post('upload', [ProfilePicController::class, 'upload']);

Route::post('/reset', [JWTAuthController::class, 'resetPassword']);

Route::post('/populate', [PopulateBooksController::class, 'populate']);

Route::post('/imgPopulate', [PopulateBooksController::class, 'addPlaceHolderImg']);

Route::post('/getFeaturedBooks', [GetBooksController::class, 'getFeaturedBooks']);

