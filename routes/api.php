<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login',App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout',App\Http\Controllers\Api\LogoutController::class)->name('logout');

// buat ngecek token user
Route::middleware('auth:api')->get('/user',function(Request $request){
    return $request->user();
});

Route::apiResource('/products',ProductController::class)->middleware('auth:api');