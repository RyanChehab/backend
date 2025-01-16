<?php

use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Controllers\AiController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\WriterMiddleware;
use App\Http\Controllers\JWTAuthController;
use App\Http\controllers\FictionController;
use App\Http\Controllers\GetBooksController;
use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\ProfilePicController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\PopulateBooksController;
use App\Http\Controllers\GutenBergController;

//Auth
Route::group(['prefix' => 'auth'],function(){
    Route::post('/signup',[JWTAuthController::class, 'signup']);
    Route::post('/login',[JWTAuthController::class, 'login']);
    Route::post('/logout',[JWTAuthController::class, 'logout']);
    Route::post('/reset', [JWTAuthController::class, 'resetPassword']);
    Route::post('/getUsers', [JWTAuthController::class, 'getAllUsers']);
});

// Admin
Route::middleware(AdminMiddleware::class)->group(function(){
    Route::post('/AddAdmin', [JWTAuthController::class, 'AddAdmin']);
    Route::post('/block_user',[JWTAuthController::class, 'block_user']);
    Route::post('/unblock_user',[JWTAuthController::class, 'Unblock_user']);
    Route::post('/delete_user',[JWTAuthController::class, 'delete_user']);
});

// Bookmarks
Route::group(['prefix' => 'bookmarks'],function(){

    Route::post('/bookmark',[BookmarksController::class, 'bookmark']);
    Route::post('/unbookmark',[BookmarksController::class, 'removeBookmark']);
    Route::post('/getBookmarks',[BookmarksController::class, 'getBookmarks']);

});

Route::post('upload', [ProfilePicController::class, 'upload']);

// Book routes
Route::group(['prefix' => 'book'],function(){

    Route::post('/populate', [PopulateBooksController::class, 'populate']);

    Route::post('/imgPopulate', [PopulateBooksController::class, 'addPlaceHolderImg']);

    Route::post('/getFeaturedBooks', [GetBooksController::class, 'getFeaturedBooks']);

    Route::get('/showBook/{gutenberg_id}', [GetBooksController::class, 'showbook']);

    Route::post('/BookCategories',[GetBooksController::class, 'getBookByCategory']);

    Route::post('/fetchBookContent', [GutenBergController::class, 'fetchBookContent']);
});

// repositories
Route::middleware(WriterMiddleware::class)->group(function(){

    Route::post('createRepo',[RepositoryController::class, 'createRepository']);

    Route::post('generate-image',[AiController::class,'generateAndStoreImage']);
    
    Route::post('updateRepo/{id}', [RepositoryController::class, 'updateRepository']);

});

// fanfiction
Route::middleware(WriterMiddleware::class)->group(function(){

    Route::post('/storeFiction/{id}' , [FictionController::class , 'storeFiction']);
    
    Route::post('deleteRepo/{id}', [FictionController::class , 'deleteRepo']);

});

Route::get('/getFiction/{id}', [FictionController::class,'getFiction']); 

Route::post('getRepositories', [RepositoryController::class , 'getRepositories']);

Route::post('getReaderRepositories', [RepositoryController::class, 'getReaderRepositories']);