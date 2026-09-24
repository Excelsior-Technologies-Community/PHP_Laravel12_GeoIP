<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GeoIP Visitor Map</title>


    <!-- Leaflet CSS -->

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .navbar {
            background: #212529;
            color: white;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
        }

        .container {
            max-width: 1350px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            margin-bottom: 5px;
        }

        .header p {
            color: #777;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-title {
            color: #777;
            font-size: 14px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;
            margin-top: 5px;
        }

        .map-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        #map {
            width: 100%;
            height: 650px;
            border-radius: 10px;
        }

        .popup-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .no-data {
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            color: #777;
        }

        @media (max-width: 700px) {

            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .stats {
                flex-direction: column;
            }

            #map {
                height: 500px;
            }

        }

    </style>

</head>


<body>


<div class="navbar">

    <h2>🌍 GeoIP System</h2>

    <div class="nav-links">

        <a href="{{ route('geo.detect') }}">
            Detect
        </a>

        <a href="{{ route('geo.dashboard') }}">
            Dashboard
        </a>

        <a href="{{ route('geo.visitors') }}">
            Visitors
        </a>

        <a href="{{ route('geo.map') }}">
            Map
        </a>

        <a href="{{ route('geo.location-insights') }}">
            Insights
        </a>

        <a href="{{ route('geo.firewall') }}">
            Firewall
        </a>

        <a href="{{ route('geo.heatmap') }}">
            Heatmap
        </a>

        <a href="{{ route('geo.localization') }}">
            Localization
        </a>

    </div>

</div>


<div class="container">


    <div class="header">

        <h1>🗺️ Interactive Visitor Location Map</h1>

        <p>
            All stored GeoIP visitor locations are displayed on the map.
        </p>

    </div>


    <div class="stats">

        <div class="stat-card">

            <div class="stat-title">
                Mapped Locations
            </div>

            <div class="stat-number">
                {{ $totalLocations }}
            </div>

        </div>

    </div>


    @if($totalLocations > 0)

        <div class="map-card">

            <div id="map"></div>

        </div>

    @else

        <div class="no-data">

            <h2>
                📍 No Location Data Available
            </h2>

            <p>
                Detect some IP addresses first to display visitor locations on the map.
            </p>

            <a href="{{ route('geo.detect') }}">
                Detect Location
            </a>

        </div>

    @endif


</div>


<!-- Leaflet JS -->

<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>


@if($totalLocations > 0)

<script>

    /*
    |--------------------------------------------------------------------------
    | Initialize Map
    |--------------------------------------------------------------------------
    */

    const map = L.map('map').setView(
        [20.5937, 78.9629],
        4
    );


    /*
    |--------------------------------------------------------------------------
    | OpenStreetMap Tiles
    |--------------------------------------------------------------------------
    */

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    /*
    |--------------------------------------------------------------------------
    | Visitor Locations
    |--------------------------------------------------------------------------
    */

    const visitors = @json($visitors);


    /*
    |--------------------------------------------------------------------------
    | Add Visitor Markers
    |--------------------------------------------------------------------------
    */

    const markers = [];


    visitors.forEach(function(visitor) {

        const latitude = parseFloat(visitor.latitude);
        const longitude = parseFloat(visitor.longitude);


        if (!isNaN(latitude) && !isNaN(longitude)) {

            const marker = L.marker([
                latitude,
                longitude
            ]).addTo(map);


            marker.bindPopup(`
                <div>

                    <div class="popup-title">
                        📍 Visitor Location
                    </div>

                    <strong>IP:</strong>
                    ${visitor.ip_address ?? 'N/A'}
                    <br>

                    <strong>Country:</strong>
                    ${visitor.country ?? 'N/A'}
                    <br>

                    <strong>City:</strong>
                    ${visitor.city ?? 'N/A'}
                    <br>

                    <strong>Latitude:</strong>
                    ${visitor.latitude ?? 'N/A'}
                    <br>

                    <strong>Longitude:</strong>
                    ${visitor.longitude ?? 'N/A'}
                    <br>

                    <strong>Detected:</strong>
                    ${visitor.created_at ?? 'N/A'}

                </div>
            `);


            markers.push(marker);

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Automatically Fit Map To All Locations
    |--------------------------------------------------------------------------
    */

    if (markers.length > 0) {

        const group = L.featureGroup(markers);

        map.fitBounds(
            group.getBounds(),
            {
                padding: [40, 40]
            }
        );

    }

</script>

@endif


</body>

</html>