<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoController;


/*
|--------------------------------------------------------------------------
| Existing GeoIP Detection
|--------------------------------------------------------------------------
*/

Route::get('/detect-location', [GeoController::class, 'detectLocation'])
    ->name('geo.detect');


/*
|--------------------------------------------------------------------------
| GeoIP Visitor Analytics Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/geo-dashboard', [GeoController::class, 'dashboard'])
    ->name('geo.dashboard');


/*
|--------------------------------------------------------------------------
| Advanced Visitor Search & Filtering
|--------------------------------------------------------------------------
*/

Route::get('/geo-visitors', [GeoController::class, 'visitors'])
    ->name('geo.visitors');


/*
|--------------------------------------------------------------------------
| Interactive Visitor Location Map
|--------------------------------------------------------------------------
*/

Route::get('/geo-map', [GeoController::class, 'map'])
    ->name('geo.map');


/*
|--------------------------------------------------------------------------
| Country & City Location Insights
|--------------------------------------------------------------------------
*/

Route::get('/geo-location-insights', [GeoController::class, 'locationInsights'])
    ->name('geo.location-insights');