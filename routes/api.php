<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentarController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function() {
        // untuk logout
        Route::get("/logout" , 'logout');

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
        Route::get('/dashboard/admin/post/show/{id}', 'show');
        Route::post('/dashboard/admin/post', 'store');
        Route::put('/dashboard/admin/post/{id}', 'update')->middleware('post');
        Route::delete('/dashboard/admin/post/delete/{id}', 'destroy')->middleware('post');
    });

    // Router untuk crud membuat comment
    Route::controller(CommentarController::class)->group(function() {
        Route::get('/comentar', 'index');
        Route::post('/comentar', 'store')->middleware('throttle:6,10');
        Route::put('/comentar/{id}', 'update')->middleware(['commentar', 'throttle:6,10']);
        Route::delete('/comentar/delete/{id}', 'destroy')->middleware('commentar');
    });


});

Route::controller(AuthController::class)->group(function (){

    Route::post("/register",'register');
    Route::post("/login",'login')->middleware("throttle");


       // reset password
       Route::post('/forgot-password', 'forgotPassword')->middleware("throttle:6,10");
       Route::post('/reset-password', 'reset')->middleware("throttle:6,10");

});


// route untuk post
Route::controller(PostController::class)->middleware("auth:sanctum")->group(function () {
    Route::get('/post', 'index');
    Route::post('/post', 'store')->middleware("throttle:6,10");
    Route::put('/post/{id}', 'update')->middleware("throttle:6,10");
    Route::delete('/post/delete/{id}', 'destroy');
});
