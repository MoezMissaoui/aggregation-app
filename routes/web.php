<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Artisan Commands Routes
Route::get('/artisan/migrate/{action?}', function ($action = '') {
    try {
        Artisan::call($action === '' ? 'migrate' : 'migrate:' . $action);
        return 'Migration completed successfully';
    } catch (\Exception $e) {
        return 'Migration failed: ' . $e->getMessage();
    }
});

Route::get('/artisan/optimize-clear', function () {
    try {
        Artisan::call('optimize:clear');
        return 'Cache cleared successfully';
    } catch (\Exception $e) {
        return 'Cache clear failed: ' . $e->getMessage();
    }
});

Route::get('/', function() {
    return view('welcome');
});