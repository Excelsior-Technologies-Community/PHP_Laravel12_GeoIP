<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Live Traffic World Heatmap Visualizer</title>
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Heat Plugin CDN -->
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #0f172a; color: #f8fafc; }
        .navbar { background: #1e293b; padding: 14px 28px; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #334155; }
        .navbar h2 { margin: 0; font-size: 18px; font-weight: 700; color: #38bdf8; }
        .nav-links { display: flex; gap: 12px; flex-wrap: wrap; }
        .nav-links a { color: #cbd5e1; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 13.5px; }
        .nav-links a:hover, .nav-links a.active { background: #334155; color: white; }
        .container { max-width: 1350px; margin: 25px auto; padding: 0 20px; }
        .header { margin-bottom: 20px; }
        .header h1 { margin: 0 0 5px; font-size: 24px; color: #f1f5f9; }
        .header p { margin: 0; color: #94a3b8; }
        .pulse-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 20px; }
        .stat-card { background: #1e293b; border: 1px solid #334155; padding: 20px; border-radius: 12px; }
        .stat-title { color: #94a3b8; font-size: 13px; margin-bottom: 6px; }
        .stat-number { font-size: 28px; font-weight: bold; color: #38bdf8; }
        .map-wrapper { position: relative; background: #1e293b; border: 1px solid #334155; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        #heatmapContainer { height: 580px; width: 100%; background: #0f172a; }
        .heatmap-controls { position: absolute; top: 15px; right: 15px; z-index: 1000; background: rgba(30, 41, 59, 0.9); backdrop-filter: blur(8px); border: 1px solid #334155; padding: 15px; border-radius: 10px; width: 240px; }
        .heatmap-controls h4 { margin: 0 0 10px; font-size: 14px; color: #38bdf8; }
        .ctrl-group { margin-bottom: 10px; }
        .ctrl-group label { display: block; font-size: 12px; color: #cbd5e1; margin-bottom: 4px; }
        .ctrl-group input { width: 100%; }
        .clusters-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
        .cluster-card { background: #1e293b; border: 1px solid #334155; padding: 16px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
        .pulse-badge { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981; animation: pulse 1.5s infinite; margin-right: 6px; }
        @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }
        @media (max-width: 900px) { .pulse-stats { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>🔥 Real-Time GeoIP Traffic Heatmap</h2>
        <div class="nav-links">
            <a href="{{ route('geo.dashboard') }}">Dashboard</a>
            <a href="{{ route('geo.detect') }}">Detect</a>
            <a href="{{ route('geo.visitors') }}">Visitors</a>
            <a href="{{ route('geo.map') }}">Map</a>
            <a href="{{ route('geo.location-insights') }}">Insights</a>
            <a href="{{ route('geo.firewall') }}">Firewall</a>
            <a href="{{ route('geo.heatmap') }}" class="active">Heatmap</a>
            <a href="{{ route('geo.localization') }}">Localization</a>
        </div>
    </div>

    <div class="container">

        <div class="header">
            <h1>🔥 Real-Time Live Traffic World Heatmap Visualizer</h1>
            <p>Visualizing global visitor density gradient, thermal activity clusters, and live regional traffic pulse.</p>
        </div>

        <div class="pulse-stats">
            <div class="stat-card">
                <div class="stat-title"><span class="pulse-badge"></span> Live Heat Points Tracked</div>
                <div class="stat-number">{{ $totalHeatPoints }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Top Traffic Cluster</div>
                <div class="stat-number" style="color: #10b981;">{{ $topClusters->first()->country ?? 'None' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Thermal Intensity Radius</div>
                <div class="stat-number" style="color: #f59e0b;" id="radiusVal">25 px</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Active Data Feed Status</div>
                <div class="stat-number" style="color: #a855f7; font-size: 20px;">⚡ LIVE PULSE STREAM</div>
            </div>
        </div>

        <div class="map-wrapper">
            <div id="heatmapContainer"></div>

            <div class="heatmap-controls">
                <h4>🎛 Heatmap Controls</h4>
                <div class="ctrl-group">
                    <label>Heat Radius (<span id="lblRadius">25</span>px)</label>
                    <input type="range" id="rngRadius" min="5" max="50" value="25">
                </div>
                <div class="ctrl-group">
                    <label>Blur Intensity (<span id="lblBlur">15</span>px)</label>
                    <input type="range" id="rngBlur" min="5" max="30" value="15">
                </div>
                <div class="ctrl-group">
                    <label>Min Opacity (<span id="lblOpacity">0.4</span>)</label>
                    <input type="range" id="rngOpacity" min="1" max="10" value="4">
                </div>
            </div>
        </div>

        <div style="margin-top: 25px;">
            <h3 style="margin-bottom: 12px; color: #f1f5f9;">📍 Top Global Traffic Density Clusters</h3>
            <div class="clusters-grid">
                @foreach($topClusters as $cluster)
                    <div class="cluster-card">
                        <div>
                            <span class="pulse-badge"></span>
                            <strong style="color: #f8fafc;">{{ $cluster->country }}</strong>
                        </div>
                        <span style="background: #334155; padding: 4px 10px; border-radius: 20px; font-weight: bold; color: #38bdf8; font-size: 13px;">
                            {{ $cluster->total }} Visits
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Leaflet Map centered globally
            const map = L.map('heatmapContainer', {
                center: [20.0, 0.0],
                zoom: 2,
                minZoom: 2,
                maxZoom: 18
            });

            // Dark Mode Tile Layer
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://carto.com/">CARTO</a> & OpenStreetMap',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            // Raw Heat Points array from PHP backend
            const rawHeatData = {!! $heatPointsJson !!};

            // Create Leaflet Heat Layer
            let heatLayer = L.heatLayer(rawHeatData, {
                radius: 25,
                blur: 15,
                maxZoom: 10,
                minOpacity: 0.4,
                gradient: {
                    0.2: '#0000ff',
                    0.4: '#00ffff',
                    0.6: '#00ff00',
                    0.8: '#ffff00',
                    1.0: '#ff0000'
                }
            }).addTo(map);

            // Dynamic Control Handlers
            const rngRadius = document.getElementById('rngRadius');
            const rngBlur = document.getElementById('rngBlur');
            const rngOpacity = document.getElementById('rngOpacity');

            const lblRadius = document.getElementById('lblRadius');
            const lblBlur = document.getElementById('lblBlur');
            const lblOpacity = document.getElementById('lblOpacity');
            const radiusVal = document.getElementById('radiusVal');

            function updateHeatmapOptions() {
                const r = parseInt(rngRadius.value);
                const b = parseInt(rngBlur.value);
                const op = parseFloat(rngOpacity.value) / 10;

                lblRadius.innerText = r;
                lblBlur.innerText = b;
                lblOpacity.innerText = op;
                radiusVal.innerText = r + ' px';

                heatLayer.setOptions({
                    radius: r,
                    blur: b,
                    minOpacity: op
                });
            }

            rngRadius.addEventListener('input', updateHeatmapOptions);
            rngBlur.addEventListener('input', updateHeatmapOptions);
            rngOpacity.addEventListener('input', updateHeatmapOptions);
        });
    </script>
</body>
</html>
