<?php

use App\Http\Controllers\API\V1\Partner\AuthTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\TestController;
use App\Http\Controllers\API\V1\Partner\PartnerAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->group(function () {

    // Partner Authentication (Public endpoints)
    Route::prefix('partner')->group(function () {
        Route::post('/auth/token', AuthTokenController::class)->name('partner.auth.token');
    });

    // Protected endpoints (require API authentication)
    Route::middleware(['api.rate_limit', 'api.key'])->group(function () {
        
        
    });

    
});

// Health check endpoint
Route::get('/health', function () {

    dd(
        encrypt_sensitive('testrfgrfeqfzefzef', 'dfgrdgdfgergrtd'),
        decrypt_sensitive(encrypt_sensitive('testrfgrfeqfzefzef', 'dfgrdgdfgergrtd'), 'dfgrdgdfgergrtd'),


    );

    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'service' => 'aggregation-api',
        'version' => '1.0.0'
    ]);
})->name('api.health');