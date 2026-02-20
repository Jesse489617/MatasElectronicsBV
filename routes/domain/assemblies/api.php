<?php

use App\Http\Controllers\AssemblyController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;


Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::post('/create', [AssemblyController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->name('api.assemblies.store');
});


