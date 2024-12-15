<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;




Route::prefix('auth')->group(function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});


//Route::middleware(['auth:api'])->group(function(){
//       Route::resource('users', UserController::class);
//});


Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
