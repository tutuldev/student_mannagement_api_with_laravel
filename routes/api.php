<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::prefix('v1')->group(function () {
     Route::post('/login',[AuthController::class, 'login']);
     Route::post('/register',[AuthController::class, 'register']);
     Route::post('/forgot',[ForgotPasswordController::class, 'forgotPassword']);

     Route::get('/login',function(){
         return response()->json([
            'message'=> 'unauthorized'
        ], 401);
     })->name('login');

     Route::middleware('auth:api')->group(function () {
         Route::post('/logout',[AuthController::class, 'logout']);
     });
});
