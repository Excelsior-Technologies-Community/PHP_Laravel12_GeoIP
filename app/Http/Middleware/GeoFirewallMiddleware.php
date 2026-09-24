<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class GeoFirewallMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Exclude firewall admin routes to prevent admin lockouts
        $allowedPatterns = [
            'geo-firewall',
            'geo-firewall/*',
            'up',
        ];

        foreach ($allowedPatterns as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        $enabled = Cache::get('geo_firewall_enabled', false);

        if (!$enabled) {
            return $next($request);
        }

        $mode = Cache::get('geo_firewall_mode', 'blacklist'); // 'blacklist' or 'whitelist'
        $blacklistedCountries = Cache::get('geo_firewall_countries', ['China', 'Russia', 'North Korea']);
        $blacklistedIps = Cache::get('geo_firewall_ips', ['192.168.1.100']);
        $whitelistedCountries = Cache::get('geo_firewall_whitelist_countries', ['India', 'United States']);

        $ip = $request->get('ip') ?: $request->ip();

        // Default test IP if localhost
        if ($ip === '127.0.0.1' || $ip === '::1') {
            $ip = '49.36.0.1';
        }

        $country = 'Unknown';
        $city = 'Unknown';
        $iso = 'us';

        try {
            if (function_exists('geoip')) {
                $location = geoip($ip);
                $country = $location->country ?? 'Unknown';
                $city = $location->city ?? 'Unknown';
                $iso = strtolower($location->iso_code ?? 'us');
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        $isBlocked = false;
        $reason = '';

        if ($mode === 'blacklist') {
            if (in_array($ip, $blacklistedIps, true)) {
                $isBlocked = true;
                $reason = "IP Address {$ip} is explicitly blacklisted by Geo-Firewall rules.";
            } elseif (in_array(strtolower($country), array_map('strtolower', $blacklistedCountries), true)) {
                $isBlocked = true;
                $reason = "Traffic from country '{$country}' is blocked by active Geo-Firewall blacklist rules.";
            }
        } else { // Whitelist Mode
            if (!in_array(strtolower($country), array_map('strtolower', $whitelistedCountries), true)) {
                $isBlocked = true;
                $reason = "Country '{$country}' is not included in the allowed Whitelist.";
            }
        }

        if ($isBlocked) {
            // Log blocked attempt
            $logs = Cache::get('geo_firewall_blocked_logs', []);

            array_unshift($logs, [
                'ip' => $ip,
                'country' => $country,
                'city' => $city,
                'reason' => $reason,
                'url' => $request->fullUrl(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);

            Cache::forever('geo_firewall_blocked_logs', array_slice($logs, 0, 50));

            return response()->view('errors.403-geo', [
                'ip' => $ip,
                'country' => $country,
                'city' => $city,
                'reason' => $reason,
                'iso' => $iso,
            ], 403);
        }

        return $next($request);
    }
}
