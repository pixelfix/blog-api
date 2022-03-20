<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Auth
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);

// Tags
Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{tag:slug}', [TagController::class, 'show']);

// Posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/featured', [PostController::class, 'featured']);
Route::get('/posts/latest', [PostController::class, 'latest']);
Route::get('/posts/gallery', [PostController::class, 'gallery']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/posts/{post:slug}/comments', [PostCommentController::class, 'index']);

Route::get('/search', [SearchController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::patch('/users/verify-email', [UserController::class, 'verifyEmail']);
    Route::get('/users/resend-verify-email', [UserController::class, 'resendVerifyEmail']);
});

Route::middleware(['auth:sanctum', 'EmailVerified'])->group(function () {
    // Route::post('/categories', [CategoryController::class, 'store']);
    // Route::patch('/categories/{category:slug}', [CategoryController::class, 'update']);

    Route::middleware(['can:create,App\Models\Post'])->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::patch('/posts/{post:slug}', [PostController::class, 'update']);
        Route::delete('/posts/{post:slug}', [PostController::class, 'destroy']);
    });
    // Route::patch('/posts/{post:slug}/add-tag', [PostController::class, 'addTag']);

    Route::post('/posts/{post:slug}/comments', [PostCommentController::class, 'store']);

    // Route::post('/tags', [TagController::class, 'store']);
    // Route::patch('/tags/{tag:slug}', [TagController::class, 'update']);
});
