<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GeoIP Analytics Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        .navbar {
            background: #212529;
            padding: 16px 30px;
            color: white;
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
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 5px;
        }

        .header p {
            color: #777;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-title {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .badge {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .recent {
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #212529;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">

    <h2>🌍 GeoIP System</h2>

    <div class="nav-links">
        <a href="{{ route('geo.detect') }}">Detect</a>
        <a href="{{ route('geo.dashboard') }}">Dashboard</a>
        <a href="{{ route('geo.visitors') }}">Visitors</a>
        <a href="{{ route('geo.map') }}">Map</a>
        <a href="{{ route('geo.location-insights') }}">Insights</a>
    </div>

</div>


    <div class="container">

        <div class="header">

            <h1>📊 GeoIP Visitor Analytics</h1>

            <p>
                Overview of visitor locations detected through GeoIP.
            </p>

        </div>


        <!-- Statistics -->

        <div class="stats">

            <div class="stat-card">

                <div class="stat-title">
                    Total Visitors
                </div>

                <div class="stat-number">
                    {{ $totalVisitors }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-title">
                    Unique IP Addresses
                </div>

                <div class="stat-number">
                    {{ $uniqueIps }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-title">
                    Countries
                </div>

                <div class="stat-number">
                    {{ $totalCountries }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-title">
                    Cities
                </div>

                <div class="stat-number">
                    {{ $totalCities }}
                </div>

            </div>

        </div>


        <!-- Country and City Statistics -->

        <div class="content-grid">


            <!-- Top Countries -->

            <div class="card">

                <h2>🌎 Top Countries</h2>

                <table>

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Country</th>
                            <th>Visitors</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($topCountries as $index => $country)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $country->country }}
                                </td>

                                <td>
                                    <span class="badge">
                                        {{ $country->total }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3">
                                    No country data available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <!-- Top Cities -->

            <div class="card">

                <h2>🏙️ Top Cities</h2>

                <table>

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>City</th>
                            <th>Visitors</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($topCities as $index => $city)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $city->city }}
                                </td>

                                <td>
                                    <span class="badge">
                                        {{ $city->total }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3">
                                    No city data available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Recent Visitors -->

        <div class="card recent">

            <h2>🕒 Recent Visitors</h2>

            <table>

                <thead>

                    <tr>
                        <th>IP Address</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Date</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($recentVisitors as $visitor)

                        <tr>

                            <td>
                                {{ $visitor->ip_address }}
                            </td>

                            <td>
                                {{ $visitor->country ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $visitor->city ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $visitor->latitude ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $visitor->longitude ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $visitor->created_at->format('d M Y, h:i A') }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">
                                No visitors found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            <a href="{{ route('geo.visitors') }}" class="btn">
                View All Visitors
            </a>

        </div>

    </div>

</body>

</html>