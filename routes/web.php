<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoController;

Route::get('/detect-location', [GeoController::class, 'detectLocation']);
