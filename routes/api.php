<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\TestController;
use App\Http\Controllers\API\V1\ServiceController;
use App\Http\Controllers\API\V1\SubscriptionController;
use App\Http\Controllers\API\V1\TransactionController;

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
    
    // Test endpoint
    Route::get('/test', [TestController::class, 'index'])->name('api.test');
    Route::post('/test', [TestController::class, 'store'])->name('api.test.store');
    
    // Public endpoints (no authentication required)
    Route::prefix('public')->group(function () {
        Route::get('/services', [ServiceController::class, 'index'])->name('api.services.index');
        Route::get('/services/{id}', [ServiceController::class, 'show'])->name('api.services.show');
    });
    
    // Protected endpoints (require API authentication)
    Route::middleware(['api.rate_limit', 'api.key'])->group(function () {
        
        // API info
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'API authenticated successfully',
                'data' => [
                    'api_key_used' => $request->get('api_key_used'),
                    'timestamp' => now(),
                    'version' => config('api.version.current', '1.0.0')
                ]
            ]);
        });
        
        // Services management
        Route::apiResource('services', ServiceController::class)->except(['index', 'show']);
        
        // Subscriptions management
        Route::apiResource('subscriptions', SubscriptionController::class);
        Route::post('/subscriptions/{id}/activate', [SubscriptionController::class, 'activate'])->name('api.subscriptions.activate');
        Route::post('/subscriptions/{id}/deactivate', [SubscriptionController::class, 'deactivate'])->name('api.subscriptions.deactivate');
        
        // Transactions management
        Route::apiResource('transactions', TransactionController::class)->only(['index', 'show', 'store']);
        Route::get('/transactions/subscriber/{subscriberId}', [TransactionController::class, 'bySubscriber'])->name('api.transactions.by_subscriber');
        
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
})->name('api.health');