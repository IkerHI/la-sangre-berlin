<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth endpoints need web middleware (session) for Sanctum SPA auth to work
Route::post('/api/login',  [AuthController::class, 'login'])->middleware('throttle:10,15');
Route::post('/api/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/api/me',      [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
