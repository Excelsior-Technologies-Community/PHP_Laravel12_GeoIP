<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class GeoController extends Controller
{
    public function detectLocation(Request $request)
    {
        // Retrieve IP from query parameter or use the client's real IP address
        $ip = $request->get('ip', $request->ip());

        // Replace localhost IP with a default test IP for local development
        if ($ip == "127.0.0.1") {
            $ip = "49.36.0.1"; // Default India test IP
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

        // Return location data to the Blade view for display
        return view('geo.detect', [
            'ip' => $ip,
            'country' => $location->country ?? 'Not Available',
            'city' => $location->city ?? 'Not Available',
            'latitude' => $location->lat ?? null,
            'longitude' => $location->lon ?? null,
            'iso' => $location->iso_code ?? 'us'
        ]);
    }
}
