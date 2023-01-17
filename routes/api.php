<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





/**Public Routes*/
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);



Route::group(['middleware' => ['auth:sanctum']], function(){
    /**Protected Routes*/
    Route::post('/logout',              [AuthController::class, 'logout']);
    Route::resource('/tasks',TasksController::class);
});
