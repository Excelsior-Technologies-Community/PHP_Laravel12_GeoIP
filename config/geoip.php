<?php

return [

    // Enable logging when a GeoIP lookup fails
    'log_failures' => true,

    // Include currency information based on country ISO code
    'include_currency' => true,

    // Set the default GeoIP service driver
    'service' => 'maxmind_database',

    // Configure available GeoIP services
    'services' => [

        // MaxMind local database configuration
        'maxmind_database' => [
            'class' => \Torann\GeoIP\Services\MaxMindDatabase::class, // Service class used for database lookup
            'database_path' => storage_path('app/geoip/GeoLite2-City.mmdb'), // Path to GeoLite2 database file
            'locales' => ['en'], // Language preference for location data
        ],
    ],

    // Enable caching for GeoIP lookups
    'cache' => 'all',

    // Disable cache tagging (file cache does not support tags)
    'cache_tags' => [],  

    // Cache expiration time in minutes
    'cache_expires' => 30,

    // Default fallback location if IP lookup fails
    'default_location' => [
        'ip' => '127.0.0.1', // Default IP address
        'iso_code' => 'IN', // Country ISO code
        'country' => 'India', // Country name
        'city' => 'Ahmedabad', // City name
        'state' => 'GJ', // State short code
        'state_name' => 'Gujarat', // Full state name
        'postal_code' => '380001', // Postal code
        'lat' => 23.0225, // Latitude coordinate
        'lon' => 72.5714, // Longitude coordinate
        'timezone' => 'Asia/Kolkata', // Timezone
        'continent' => 'AS', // Continent code
        'default' => true, // Indicates this is a fallback location
        'currency' => 'INR', // Default currency
    ],

];
