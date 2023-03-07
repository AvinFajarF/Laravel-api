<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentarController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function() {
        // untuk logout
        Route::get("/logout" , 'logout');

        // reset password
        Route::post('/reset-password', 'forgotPassword');

    });

    // untuk update user
    Route::put("/user/update/{id}",[MyProfileController::class, 'update']);


    // Router untuk crud membuat user hanya bisa role superadmin
    Route::controller(UserController::class)->middleware('superadmin')->group(function() {
        Route::get('/dashboard/admin/user', 'index');
        Route::post('/dashboard/admin/user', 'store');
        Route::put('/dashboard/admin/user/{id}', 'edit');
        Route::delete('/dashboard/admin/user/delete/{id}', 'destroy');
    });


     // Router untuk crud membuat post hanya bisa role superadmin
     Route::controller(PostController::class)->middleware('superadmin')->group(function() {
        Route::get('/dashboard/admin/post', 'index');
        Route::get('/dashboard/admin/post/show/{id}', 'show');
        Route::post('/dashboard/admin/post', 'store');
        Route::put('/dashboard/admin/post/{id}', 'update')->middleware('post');
        Route::delete('/dashboard/admin/post/delete/{id}', 'destroy')->middleware('post');
    });

    // Router untuk crud membuat comment
    Route::controller(CommentarController::class)->group(function() {
        Route::get('/comentar', 'index');
        Route::post('/comentar', 'store');
        Route::put('/comentar/{id}', 'update')->middleware('commentar');
        Route::delete('/comentar/delete/{id}', 'destroy')->middleware('commentar');
    });


});


Route::post("/register",[AuthController::class, 'register']);
Route::post("/login",[AuthController::class, 'login']);

