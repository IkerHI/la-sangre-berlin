<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\ConcertController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\SpotifyController;
use App\Http\Controllers\Api\TechSpecController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\Admin;
use Illuminate\Support\Facades\Route;

// Auth routes are in routes/web.php (need web session middleware for Sanctum SPA)

// Public
Route::get('/concerts', [ConcertController::class, 'index']);
Route::get('/concerts/{concert}', [ConcertController::class, 'show']);
Route::get('/albums', [AlbumController::class, 'index']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/tech-specs', [TechSpecController::class, 'index']);
Route::get('/rider-lines', [TechSpecController::class, 'linesConfigs']);
Route::get('/spotify/tracks', [SpotifyController::class, 'tracks']);
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:3,1');
Route::get('/settings', [SettingsController::class, 'index']);

// Admin (protected)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Concerts
    Route::get('/concerts', [Admin\ConcertController::class, 'index']);
    Route::post('/concerts', [Admin\ConcertController::class, 'store']);
    Route::put('/concerts/{concert}', [Admin\ConcertController::class, 'update']);
    Route::delete('/concerts/{concert}', [Admin\ConcertController::class, 'destroy']);
    Route::post('/concerts/sync', [Admin\ConcertController::class, 'sync']);

    // Albums
    Route::get('/albums', [Admin\AlbumController::class, 'index']);
    Route::post('/albums', [Admin\AlbumController::class, 'store']);
    Route::post('/albums/{album}', [Admin\AlbumController::class, 'update']); // POST for multipart
    Route::delete('/albums/{album}', [Admin\AlbumController::class, 'destroy']);

    // Gallery
    Route::get('/gallery', [Admin\GalleryController::class, 'index']);
    Route::post('/gallery', [Admin\GalleryController::class, 'store']);
    Route::patch('/gallery/{galleryImage}', [Admin\GalleryController::class, 'update']);
    Route::delete('/gallery/{galleryImage}', [Admin\GalleryController::class, 'destroy']);

    // Tech Specs
    Route::get('/tech-specs', [Admin\TechSpecController::class, 'index']);
    Route::post('/tech-specs', [Admin\TechSpecController::class, 'upsert']);
    Route::post('/tech-specs/pdf', [Admin\TechSpecController::class, 'uploadPdf']);
    Route::delete('/tech-specs/{techSpec}', [Admin\TechSpecController::class, 'destroySpec']);

    // Rider Lines Configs
    Route::put('/rider-lines/{config}', [Admin\TechSpecController::class, 'updateLinesConfig']);

    // Messages
    Route::get('/messages', [Admin\MessageController::class, 'index']);
    Route::patch('/messages/{contactMessage}/read', [Admin\MessageController::class, 'markRead']);
    Route::post('/messages/{contactMessage}/reply', [Admin\MessageController::class, 'reply']);
    Route::delete('/messages/{contactMessage}', [Admin\MessageController::class, 'destroy']);

    // Stats
    Route::get('/stats', [Admin\StatsController::class, 'index']);

    // Settings
    Route::get('/settings', [Admin\SettingsController::class, 'index']);
    Route::post('/settings', [Admin\SettingsController::class, 'update']);

    // Users
    Route::get('/users', [Admin\UserController::class, 'index']);
    Route::post('/users', [Admin\UserController::class, 'store']);
    Route::patch('/users/{user}/password', [Admin\UserController::class, 'updatePassword']);
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy']);
});
