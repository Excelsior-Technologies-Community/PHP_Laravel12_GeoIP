<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeoController extends Controller
{
    /**
     * Existing GeoIP location detection
     */
    public function detectLocation(Request $request)
    {
        // Retrieve IP from query parameter or use the client's real IP address
        $ip = $request->get('ip', $request->ip());

        // Replace localhost IP with a default test IP for local development
        if ($ip == "127.0.0.1") {
            $ip = "49.36.0.1";
        }

        // Fetch location details using GeoIP
        $location = geoip($ip);

        // Save visitor location data into the database
        Visitor::create([
            'ip_address' => $ip,
            'country' => $location->country,
            'city' => $location->city,
            'latitude' => $location->lat,
            'longitude' => $location->lon,
        ]);

        // Return location data to the Blade view
        return view('geo.detect', [
            'ip' => $ip,
            'country' => $location->country ?? 'Not Available',
            'city' => $location->city ?? 'Not Available',
            'latitude' => $location->lat ?? null,
            'longitude' => $location->lon ?? null,
            'iso' => $location->iso_code ?? 'us'
        ]);
    }


    /**
     * GeoIP Visitor Analytics Dashboard
     */
    public function dashboard()
    {
        $totalVisitors = Visitor::count();

        $uniqueIps = Visitor::distinct('ip_address')->count('ip_address');

        $totalCountries = Visitor::whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct('country')
            ->count('country');

        $totalCities = Visitor::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct('city')
            ->count('city');

        // Top countries
        $topCountries = Visitor::select(
                'country',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top cities
        $topCities = Visitor::select(
                'city',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Recent visitors
        $recentVisitors = Visitor::latest()
            ->limit(10)
            ->get();

        return view('geo.dashboard', compact(
            'totalVisitors',
            'uniqueIps',
            'totalCountries',
            'totalCities',
            'topCountries',
            'topCities',
            'recentVisitors'
        ));
    }


    /**
     * Advanced Visitor Search & Filtering
     */
    public function visitors(Request $request)
    {
        $query = Visitor::query();

        // Search by IP, country or city
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Country filter
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Date from filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Date to filter
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $visitors = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Countries for filter dropdown
        $countries = Visitor::whereNotNull('country')
            ->where('country', '!=', '')
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return view('geo.visitors', compact(
            'visitors',
            'countries'
        ));
    }


    /**
     * Interactive Visitor Location Map
     */
    public function map()
    {
        $visitors = Visitor::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->get();

        $totalLocations = $visitors->count();

        return view('geo.map', compact(
            'visitors',
            'totalLocations'
        ));
    }


    /**
     * Country & City Location Insights
     */
    public function locationInsights(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get available countries
        |--------------------------------------------------------------------------
        */

        $countries = Visitor::whereNotNull('country')
            ->where('country', '!=', '')
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');


        /*
        |--------------------------------------------------------------------------
        | Selected country
        |--------------------------------------------------------------------------
        */

        $selectedCountry = $request->get('country');


        /*
        |--------------------------------------------------------------------------
        | Automatically select the country with the most visitors
        |--------------------------------------------------------------------------
        */

        if (!$selectedCountry && $countries->count() > 0) {

            $selectedCountry = Visitor::select('country')
                ->selectRaw('COUNT(*) as total')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->groupBy('country')
                ->orderByDesc('total')
                ->value('country');
        }


        /*
        |--------------------------------------------------------------------------
        | Base query
        |--------------------------------------------------------------------------
        */

        $countryQuery = Visitor::query();

        if ($selectedCountry) {
            $countryQuery->where('country', $selectedCountry);
        }


        /*
        |--------------------------------------------------------------------------
        | Country statistics
        |--------------------------------------------------------------------------
        */

        $totalVisitors = (clone $countryQuery)->count();

        $uniqueIps = (clone $countryQuery)
            ->whereNotNull('ip_address')
            ->distinct('ip_address')
            ->count('ip_address');

        $totalCities = (clone $countryQuery)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct('city')
            ->count('city');


        /*
        |--------------------------------------------------------------------------
        | Top cities
        |--------------------------------------------------------------------------
        */

        $topCities = (clone $countryQuery)
            ->select(
                'city',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Visitor activity for last 7 days
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        $dailyVisitors = (clone $countryQuery)
            ->select(
                DB::raw('DATE(created_at) as visit_date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay()
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date');


        /*
        |--------------------------------------------------------------------------
        | Build complete 7-day visitor trend
        |--------------------------------------------------------------------------
        */

        $visitorTrend = collect();

        for (
            $date = $startDate->copy();
            $date->lte($endDate);
            $date->addDay()
        ) {
            $dateKey = $date->format('Y-m-d');

            $visitorTrend->push([
                'date' => $date->format('d M'),
                'full_date' => $dateKey,
                'total' => $dailyVisitors[$dateKey]->total ?? 0,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Recent visitors from selected country
        |--------------------------------------------------------------------------
        */

        $recentVisitors = (clone $countryQuery)
            ->latest()
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return view
        |--------------------------------------------------------------------------
        */

        return view('geo.location-insights', compact(
            'countries',
            'selectedCountry',
            'totalVisitors',
            'uniqueIps',
            'totalCities',
            'topCities',
            'visitorTrend',
            'recentVisitors'
        ));
    }
}