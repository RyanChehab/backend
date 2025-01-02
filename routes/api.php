<?php

use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\GetBooksController;
use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\ProfilePicController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\PopulateBooksController;

//Auth
Route::group(['prefix' => 'auth'],function(){
    Route::post('/signup',[JWTAuthController::class, 'signup']);
    Route::post('/login',[JWTAuthController::class, 'login']);
    Route::post('/logout',[JWTAuthController::class, 'logout']);
    Route::post('/reset', [JWTAuthController::class, 'resetPassword']);

});

// Blocking user
Route::middleware(AdminMiddleware::class)->group(function(){
    Route::post('/AddAdmin', [JWTAuthController::class, 'AddAdmin']);
    Route::post('/block_user',[JWTAuthController::class, 'block_user']);
    Route::post('/delete_user',[JWTAuthController::class, 'delete_user']);
});

// Bookmarks
Route::middleware(JWTMiddleware::class)->group(function(){
    Route::post('/getBookmarks', [BookmarksController::class, 'getUserBookmarks']);
    Route::post('/bookmark', [BookmarksController::class, 'toggleBookmark']);
});

Route::post('upload', [ProfilePicController::class, 'upload']);

// Book routes
Route::group(['prefix' => 'book'],function(){

    Route::post('/populate', [PopulateBooksController::class, 'populate']);

    Route::post('/imgPopulate', [PopulateBooksController::class, 'addPlaceHolderImg']);

    Route::post('/getFeaturedBooks', [GetBooksController::class, 'getFeaturedBooks']);

    Route::get('/showBook/{gutenberg_id}', [GetBooksController::class, 'showbook']);

    Route::post('/BookCategories',[GetBooksController::class, 'getBookByCategory']);
});

// Repository routes
Route::group(['prefix'=>'Repository'],function(){
    Route::post('createRepo',[RepositoryController::class, 'createRepository']);
});
