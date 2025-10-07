<?php

use App\Http\Controllers\API\V1\Partner\AuthTokenController;
use App\Http\Controllers\API\V1\Partner\AccessTokenController;
use App\Http\Controllers\API\V1\Subscription\OptinController;
use App\Http\Controllers\API\V1\Subscription\OptoutController;
use App\Http\Controllers\API\V1\Subscription\StatusController;
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
Route::prefix('v1')->middleware(['api.rate_limit', 'correlation.id'])->group(function () {


    // OAuth2 Authentication (Public endpoints)
    Route::prefix('auth')->group(function () {
        Route::post('/oauth2/token', AccessTokenController::class)->name('partner.oauth2.token');
    });
  

    // Protected endpoints (require API authentication)
    Route::middleware(['auth:partner'])->group(function () {
        
        // Subscription Management
        Route::prefix('subscription')->group(function () {
            Route::post('/optin/{partner_id}', OptinController::class)
                ->name('subscription.optin');
            Route::post('/optout/{partner_id}', OptoutController::class)
                ->name('subscription.optout');
            Route::get('/status/{partner_id}', StatusController::class)
                ->name('subscription.status');
        });

    });
    
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'service' => 'aggregation-api',
        'version' => '1.0.0'
    ]);
})->middleware(['correlation.id'])->name('api.health');