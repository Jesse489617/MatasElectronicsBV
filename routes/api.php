<?php

use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\CustomAssemblyController;
use App\Http\Controllers\DownloadInvoiceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
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

    Route::post('user/logout', LogoutController::class);
});

Route::post('user/register', [UserController::class, 'store']);
Route::post('user/login', LoginController::class);

// Component API
Route::middleware('auth:sanctum', AdminMiddleware::class)->group(function () {
    Route::post('components/create', [ComponentController::class, 'store']);
    Route::put('components/{component}', [ComponentController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/components/cart', [CartController::class, 'store']);
    Route::post('components/buy', [BuyController::class, 'store']);
});

Route::get('/components', [ComponentController::class, 'index']);
Route::get('/components/{component}', [ComponentController::class, 'show']);

// Assemblies API
Route::prefix('assemblies')
    ->group(base_path('routes/domain/assemblies/api.php'));

Route::middleware('auth:sanctum', AdminMiddleware::class)->group(function () {
    // Route::post('assemblies/create', [AssemblyController::class, 'store']);
    Route::put('assemblies/{assembly}', [AssemblyController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('assemblies/cart', [CartController::class, 'store']);
    Route::post('assemblies/buy', [BuyController::class, 'store']);
    Route::post('assemblies/custom', [CustomAssemblyController::class, 'store']);
});

Route::get('/assemblies', [AssemblyController::class, 'index']);
Route::get('/assemblies/{assembly}', [AssemblyController::class, 'show']);

// History API
Route::middleware('auth:sanctum')->get('/history', [HistoryController::class, 'index']);
Route::middleware('auth:sanctum')->get('/history/{id}/invoice', DownloadInvoiceController::class);

// Manufacturer API
Route::middleware('auth:sanctum')->get('/manufacturers', [ManufacturerController::class, 'index']);

// Cart API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::delete('/cart/items/{id}', [CartController::class, 'delete']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);
});
