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
        $ip = $request->get('ip', $request->ip());

        // Replace localhost IP with a default test IP
        if ($ip == "127.0.0.1") {
            $ip = "49.36.0.1";
        }

        // Fetch location details
        $location = geoip($ip);

        // Save visitor
        Visitor::create([
            'ip_address' => $ip,
            'country' => $location->country,
            'city' => $location->city,
            'latitude' => $location->lat,
            'longitude' => $location->lon,
        ]);

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

        $uniqueIps = Visitor::distinct('ip_address')
            ->count('ip_address');

        $totalCountries = Visitor::whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct('country')
            ->count('country');

        $totalCities = Visitor::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct('city')
            ->count('city');

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
     * Advanced Visitor Search, Filtering, Sorting and Pagination
     */
    public function visitors(Request $request)
    {
        $query = Visitor::query();

        /*
        |--------------------------------------------------------------------------
        | 1. Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Country Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. City Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Date From
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Date To
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Date Preset
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_preset')) {

            switch ($request->date_preset) {

                case 'today':
                    $query->whereDate(
                        'created_at',
                        Carbon::today()
                    );
                    break;

                case '7days':
                    $query->where(
                        'created_at',
                        '>=',
                        Carbon::now()->subDays(7)
                    );
                    break;

                case '30days':
                    $query->where(
                        'created_at',
                        '>=',
                        Carbon::now()->subDays(30)
                    );
                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 7. Coordinate Filter
        |--------------------------------------------------------------------------
        */
        if ($request->coordinate_status === 'with') {

            $query->whereNotNull('latitude')
                ->whereNotNull('longitude');

        } elseif ($request->coordinate_status === 'without') {

            $query->where(function ($q) {
                $q->whereNull('latitude')
                    ->orWhereNull('longitude');
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 8. Unique IP Filter
        |--------------------------------------------------------------------------
        |
        | Shows only the latest record for each IP address.
        |
        */
        if ($request->unique_ip === '1') {

            $latestIds = Visitor::select(DB::raw('MAX(id) as id'))
                ->groupBy('ip_address')
                ->pluck('id');

            $query->whereIn('id', $latestIds);
        }


        /*
        |--------------------------------------------------------------------------
        | 9. Sorting
        |--------------------------------------------------------------------------
        */
        $allowedSorts = [
            'id',
            'ip_address',
            'country',
            'city',
            'latitude',
            'longitude',
            'created_at',
        ];

        $sort = $request->get('sort', 'id');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        $direction = $request->get('direction', 'asc');

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);


        /*
        |--------------------------------------------------------------------------
        | 10. Records Per Page
        |--------------------------------------------------------------------------
        */
        $perPageOptions = [5, 10, 25, 50, 100];

        $perPage = (int) $request->get('per_page', 5);

        if (!in_array($perPage, $perPageOptions)) {
            $perPage = 5;
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $visitors = $query
            ->paginate($perPage)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Countries
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
        | Cities
        |--------------------------------------------------------------------------
        */
        $cities = Visitor::whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalVisitors = Visitor::count();

        $uniqueIps = Visitor::distinct('ip_address')
            ->count('ip_address');

        $filteredVisitors = $query->count();


        return view('geo.visitors', compact(
            'visitors',
            'countries',
            'cities',
            'perPage',
            'perPageOptions',
            'sort',
            'direction',
            'totalVisitors',
            'uniqueIps',
            'filteredVisitors'
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
        $countries = Visitor::whereNotNull('country')
            ->where('country', '!=', '')
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $selectedCountry = $request->get('country');

        if (!$selectedCountry && $countries->count() > 0) {

            $selectedCountry = Visitor::select('country')
                ->selectRaw('COUNT(*) as total')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->groupBy('country')
                ->orderByDesc('total')
                ->value('country');
        }

        $countryQuery = Visitor::query();

        if ($selectedCountry) {
            $countryQuery->where(
                'country',
                $selectedCountry
            );
        }

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

        $recentVisitors = (clone $countryQuery)
            ->latest()
            ->limit(10)
            ->get();

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


    /**
     * Delete Single Visitor
     */
    public function deleteVisitor(Visitor $visitor)
    {
        $visitor->delete();

        return redirect()
            ->route('geo.visitors')
            ->with('success', 'Visitor record deleted successfully.');
    }


    /**
     * Bulk Delete Visitors
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'visitor_ids' => ['required', 'array'],
            'visitor_ids.*' => ['integer'],
        ]);

        $count = Visitor::whereIn(
            'id',
            $request->visitor_ids
        )->delete();

        return redirect()
            ->route('geo.visitors')
            ->with(
                'success',
                $count . ' visitor record(s) deleted successfully.'
            );
    }


    /**
     * Export Visitors as CSV
     */
    public function exportCsv(Request $request)
    {
        $query = $this->buildVisitorQuery($request);

        $visitors = $query
            ->orderBy('id', 'asc')
            ->get();

        $filename = 'geoip-visitors-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
                'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($visitors) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'IP Address',
                'Country',
                'City',
                'Latitude',
                'Longitude',
                'Detected At',
            ]);

            foreach ($visitors as $visitor) {

                fputcsv($file, [
                    $visitor->id,
                    $visitor->ip_address,
                    $visitor->country,
                    $visitor->city,
                    $visitor->latitude,
                    $visitor->longitude,
                    $visitor->created_at,
                ]);
            }

            fclose($file);

        }, 200, $headers);
    }


    /**
     * Export Visitors as JSON
     */
    public function exportJson(Request $request)
    {
        $query = $this->buildVisitorQuery($request);

        $visitors = $query
            ->orderBy('id', 'asc')
            ->get();

        $filename = 'geoip-visitors-' .
            now()->format('Y-m-d-H-i-s') .
            '.json';

        return response()->json(
            $visitors,
            200,
            [
                'Content-Disposition' =>
                    'attachment; filename="' . $filename . '"',
            ]
        );
    }


    /**
     * Reusable Visitor Query For Exports
     */
    private function buildVisitorQuery(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");

            });
        }

        if ($request->filled('country')) {
            $query->where(
                'country',
                $request->country
            );
        }

        if ($request->filled('city')) {
            $query->where(
                'city',
                $request->city
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        if ($request->filled('date_preset')) {

            switch ($request->date_preset) {

                case 'today':

                    $query->whereDate(
                        'created_at',
                        Carbon::today()
                    );

                    break;

                case '7days':

                    $query->where(
                        'created_at',
                        '>=',
                        Carbon::now()->subDays(7)
                    );

                    break;

                case '30days':

                    $query->where(
                        'created_at',
                        '>=',
                        Carbon::now()->subDays(30)
                    );

                    break;
            }
        }

        if ($request->coordinate_status === 'with') {

            $query->whereNotNull('latitude')
                ->whereNotNull('longitude');

        } elseif ($request->coordinate_status === 'without') {

            $query->where(function ($q) {

                $q->whereNull('latitude')
                    ->orWhereNull('longitude');

            });
        }

        if ($request->unique_ip === '1') {

            $latestIds = Visitor::select(
                    DB::raw('MAX(id) as id')
                )
                ->groupBy('ip_address')
                ->pluck('id');

            $query->whereIn('id', $latestIds);
        }

        return $query;
    }
}
