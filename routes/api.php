<?php

use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return [
            'user' => $request->user(),
            'currentToken' => $request->bearerToken(),
        ];
    });

    Route::post('user/logout', [UserController::class, 'logout']);
});

Route::post('user/register', [UserController::class, 'store']);
Route::post('user/login', [UserController::class, 'auth']);

// Component API
Route::middleware('auth:sanctum', AdminMiddleware::class)->group(function () {
    Route::post('components/create', [ComponentController::class, 'store']);
    Route::put('components/{id}', [ComponentController::class, 'update']);
});

Route::middleware('auth:sanctum')->post('/components/buy', [ComponentController::class, 'buy']);
Route::get('/components', [ComponentController::class, 'index']);
Route::get('/components/{id}', [ComponentController::class, 'show']);

// Assemblies API
Route::middleware('auth:sanctum', AdminMiddleware::class)->group(function () {
    Route::post('assemblies/create', [AssemblyController::class, 'store']);
    Route::put('assemblies/{id}', [AssemblyController::class, 'update']);
});

Route::middleware('auth:sanctum')->post('/assemblies/buy', [AssemblyController::class, 'buy']);
Route::get('/assemblies', [AssemblyController::class, 'index']);
Route::get('/assemblies/{id}', [AssemblyController::class, 'show']);

// History API
Route::middleware('auth:sanctum')->get('/history', [HistoryController::class, 'index']);

// Manufacturer API
Route::middleware('auth:sanctum')->get('/manufacturers', [ManufacturerController::class, 'index']);
