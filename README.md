# PHP_Laravel12_GeoIP

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![GeoIP](https://img.shields.io/badge/GeoIP-MaxMind-green)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

---

##  Overview

PHP_Laravel12_GeoIP is a Laravel 12 project that integrates MaxMind GeoLite2 database using the Torann GeoIP package to detect visitor location based on IP address.

The project:
- Detects country, city, latitude, longitude
- Stores visitor data in database
- Displays location with Google Maps
- Shows country flag dynamically

---

##  Features

- MaxMind GeoLite2 Database Integration
- Torann GeoIP Package Setup
- Visitor Location Detection
- Database Storage of Visitor Data
- Dynamic Country Flag Display
- Embedded Google Maps View
- Fallback Default Location
- Cache Enabled GeoIP Lookup

---
##  Folder Structure
```
Laravel12_GeoIP/
│
├── app/
│ ├── Http/Controllers/GeoController.php
│ └── Models/Visitor.php
│
├── config/
│ └── geoip.php
│
├── database/
│ └── migrations/xxxx_create_visitors_table.php
│
├── resources/views/geo/
│ └── detect.blade.php
│
├── routes/
│ └── web.php
│
└── storage/app/geoip/
└── GeoLite2-City.mmdb

```
---

## 1. Project Installation

### Create New Laravel Project

```bash
composer create-project laravel/laravel Laravel12_GeoIP
```

### Start Server

```bash
php artisan serve
```

### Open in Browser

```
http://127.0.0.1:8000
```

---

## .env Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
CACHE_DRIVER=file
```

---

## 2. Download MaxMind Database

1. Create account at:

   [https://www.maxmind.com](https://www.maxmind.com)

2. Download:

   GeoLite2-City.mmdb

   <img width="1146" height="847" alt="Screenshot 2026-02-12 130047" src="https://github.com/user-attachments/assets/bf15f0d8-6345-474e-a847-62d4ef210c7e" />


4. Create folder inside project:

```
storage/app/geoip/
```

4. Place file here:

```
storage/app/geoip/GeoLite2-City.mmdb
```

---

## 3. Install GeoIP Package

### Install Torann GeoIP Package

```bash
composer require torann/geoip
```

### Install Required MaxMind Dependency

```bash
composer require geoip2/geoip2
```

### Publish Config File

```bash
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider"
```

---

## 4. Configure GeoIP

Open:

```
config/geoip.php
```

Replace with:

```php
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
            'class' => \Torann\GeoIP\Services\MaxMindDatabase::class,
            'database_path' => storage_path('app/geoip/GeoLite2-City.mmdb'),
            'locales' => ['en'],
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
        'ip' => '127.0.0.1',
        'iso_code' => 'IN',
        'country' => 'India',
        'city' => 'Ahmedabad',
        'state' => 'GJ',
        'state_name' => 'Gujarat',
        'postal_code' => '380001',
        'lat' => 23.0225,
        'lon' => 72.5714,
        'timezone' => 'Asia/Kolkata',
        'continent' => 'AS',
        'default' => true,
        'currency' => 'INR',
    ],

];
```

### Clear Cache

```bash
php artisan config:clear

php artisan cache:clear
```

---

## 5. Create Visitor Model & Migration

```bash
php artisan make:model Visitor -m
```

### Migration File

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
```

Run migration:

```bash
php artisan migrate
```

---

### Visitor Model

File: `app/Models/Visitor.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'country',
        'city',
        'latitude',
        'longitude',
    ];
}
```

---

## 6. Create Controller

```bash
php artisan make:controller GeoController
```

File: `app/Http/Controllers/GeoController.php`

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class GeoController extends Controller
{
    public function detectLocation(Request $request)
    {
        $ip = $request->get('ip', $request->ip());

        if ($ip == "127.0.0.1") {
            $ip = "49.36.0.1";
        }

        $location = geoip($ip);

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
}
```

---

## 7. Route

File: `routes/web.php`

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoController;

Route::get('/detect-location', [GeoController::class, 'detectLocation']);
```

---

## 8. Create Blade View (UI)

File: `resources/views/geo/detect.blade.php`
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Geo Location</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            width: 450px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            text-align: center;
            color: white;
        }

        h2 {
            margin-bottom: 25px;
        }

        .info {
            margin: 8px 0;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
        }

        .badge {
            margin-top: 20px;
            padding: 10px 20px;
            background: #ffffff;
            color: #764ba2;
            border-radius: 25px;
            font-weight: bold;
            display: inline-block;
        }

        iframe {
            margin-top: 20px;
            width: 100%;
            height: 200px;
            border-radius: 15px;
            border: none;
        }

        .flag {
            width: 40px;
            vertical-align: middle;
            margin-left: 8px;
        }

        .footer {
            margin-top: 15px;
            font-size: 12px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="card">
    <h2> Your Geo Location</h2>

    <div class="info">
        <span class="label">IP:</span> {{ $ip }}
    </div>

    <div class="info">
        <span class="label">Country:</span>
        {{ $country }}
        @if($country !== 'Not Available')
            <img class="flag"
                 src="https://flagcdn.com/48x36/{{ strtolower(geoip($ip)->iso_code ?? 'us') }}.png">
        @endif
    </div>

    <div class="info">
        <span class="label">City:</span> {{ $city }}
    </div>

    <div class="info">
        <span class="label">Latitude:</span> {{ $latitude }}
    </div>

    <div class="info">
        <span class="label">Longitude:</span> {{ $longitude }}
    </div>

    <div class="badge">
        Live Location Detected
    </div>

    @if($latitude && $longitude)
        <iframe
            src="https://maps.google.com/maps?q={{ $latitude }},{{ $longitude }}&hl=en&z=6&output=embed">
        </iframe>
    @endif

    <div class="footer">
        Laravel GeoIP Advanced Integration
    </div>
</div>

</body>
</html>

---
```

## 9. Outputs

### Test India Output

```
http://127.0.0.1:8000/detect-location?ip=49.36.0.1
```
Expected Output:

<img width="615" height="683" alt="Screenshot 2026-02-12 121126" src="https://github.com/user-attachments/assets/33b1c6a6-457e-4952-9c0a-aba4ad89fee2" />

### Test USA Output

```
http://127.0.0.1:8000/detect-location?ip=73.162.0.1
```
Expected Output:

<img width="616" height="684" alt="Screenshot 2026-02-12 122014" src="https://github.com/user-attachments/assets/ff5b4e95-954e-4fb7-b861-8dc3546013fd" />

