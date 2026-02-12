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
