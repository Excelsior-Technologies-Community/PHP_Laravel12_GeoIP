<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoController;


Route::get('/', function () {
    return redirect()->route('geo.dashboard');
});

/*
|--------------------------------------------------------------------------
| GeoIP Detection
|--------------------------------------------------------------------------
*/

Route::get('/detect-location', [GeoController::class, 'detectLocation'])
    ->name('geo.detect');


/*
|--------------------------------------------------------------------------
| Analytics Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/geo-dashboard', [GeoController::class, 'dashboard'])
    ->name('geo.dashboard');


/*
|--------------------------------------------------------------------------
| Visitor Search / Filtering / Sorting
|--------------------------------------------------------------------------
*/

Route::get('/geo-visitors', [GeoController::class, 'visitors'])
    ->name('geo.visitors');


/*
|--------------------------------------------------------------------------
| Delete Single Visitor
|--------------------------------------------------------------------------
*/

Route::delete('/geo-visitors/{visitor}', [GeoController::class, 'deleteVisitor'])
    ->name('geo.visitors.delete');


/*
|--------------------------------------------------------------------------
| Bulk Delete Visitors
|--------------------------------------------------------------------------
*/

Route::delete('/geo-visitors-bulk-delete', [GeoController::class, 'bulkDelete'])
    ->name('geo.visitors.bulk-delete');


/*
|--------------------------------------------------------------------------
| Export CSV
|--------------------------------------------------------------------------
*/

Route::get('/geo-visitors-export-csv', [GeoController::class, 'exportCsv'])
    ->name('geo.visitors.export.csv');


/*
|--------------------------------------------------------------------------
| Export JSON
|--------------------------------------------------------------------------
*/

Route::get('/geo-visitors-export-json', [GeoController::class, 'exportJson'])
    ->name('geo.visitors.export.json');


/*
|--------------------------------------------------------------------------
| Interactive Map
|--------------------------------------------------------------------------
*/

Route::get('/geo-map', [GeoController::class, 'map'])
    ->name('geo.map');


/*
|--------------------------------------------------------------------------
| Location Insights
|--------------------------------------------------------------------------
*/

Route::get('/geo-location-insights', [GeoController::class, 'locationInsights'])
    ->name('geo.location-insights');


/*
|--------------------------------------------------------------------------
| Real-Time GeoIP Firewall & Country Access Control Studio
|--------------------------------------------------------------------------
*/

Route::get('/geo-firewall', [GeoController::class, 'firewall'])
    ->name('geo.firewall');

Route::post('/geo-firewall/toggle', [GeoController::class, 'toggleFirewall'])
    ->name('geo.firewall.toggle');

Route::post('/geo-firewall/rule', [GeoController::class, 'updateFirewallRule'])
    ->name('geo.firewall.rule');

Route::post('/geo-firewall/mode', [GeoController::class, 'updateFirewallMode'])
    ->name('geo.firewall.mode');

Route::post('/geo-firewall/clear-logs', [GeoController::class, 'clearFirewallLogs'])
    ->name('geo.firewall.clear-logs');


/*
|--------------------------------------------------------------------------
| Real-Time Live Traffic World Heatmap Visualizer
|--------------------------------------------------------------------------
*/

Route::get('/geo-heatmap', [GeoController::class, 'heatmap'])
    ->name('geo.heatmap');


/*
|--------------------------------------------------------------------------
| Geo-Smart Currency, Timezone & Regional Localization Studio
|--------------------------------------------------------------------------
*/

Route::get('/geo-localization', [GeoController::class, 'localization'])
    ->name('geo.localization');

