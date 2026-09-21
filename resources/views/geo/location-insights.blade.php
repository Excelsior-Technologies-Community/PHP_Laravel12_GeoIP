<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GeoIP Location Insights</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .navbar {
            background: #222;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .navbar h2 {
            margin: 0;
            color: white;
            font-size: 21px;
        }

        .nav-links {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 6px;
            background: #444;
            font-size: 13px;
        }

        .nav-links a:hover {
            background: #666;
        }

        .container {
            max-width: 1250px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #666;
        }

        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .filter-box form {
            display: flex;
            align-items: end;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 220px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            font-size: 14px;
        }

        .form-group select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
            background: white;
            font-size: 14px;
        }

        .btn {
            border: none;
            padding: 11px 20px;
            border-radius: 7px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #555;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .selected-country {
            margin-bottom: 20px;
            padding: 15px 18px;
            background: #eaf2ff;
            border-left: 5px solid #2563eb;
            border-radius: 7px;
        }

        .selected-country strong {
            font-size: 18px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-card .icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 30px;
        }

        .stat-card p {
            margin: 8px 0 0;
            color: #666;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .city-row {
            margin-bottom: 18px;
        }

        .city-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .city-name {
            font-weight: bold;
        }

        .city-count {
            color: #666;
        }

        .bar-container {
            width: 100%;
            height: 10px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .bar {
            height: 100%;
            background: #2563eb;
            border-radius: 10px;
        }

        .trend-table {
            width: 100%;
            border-collapse: collapse;
        }

        .trend-table th,
        .trend-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .trend-table th {
            background: #f8f9fa;
        }

        .trend-number {
            font-weight: bold;
        }

        .recent-table {
            width: 100%;
            border-collapse: collapse;
        }

        .recent-table th,
        .recent-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }

        .recent-table th {
            background: #f8f9fa;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 15px;
            }

            .container {
                padding: 0 12px;
            }

            .recent-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">

        <h2>🌍 GeoIP Location Insights</h2>

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

        </div>

    </div>


    <div class="container">

        <div class="header">

            <h1>🌍 Country & City Location Insights</h1>

            <p>
                Analyze GeoIP visitor activity by country, city and date.
            </p>

        </div>


        {{-- Country Filter --}}

        <div class="filter-box">

            <form method="GET" action="{{ route('geo.location-insights') }}">

                <div class="form-group">

                    <label for="country">
                        Select Country
                    </label>

                    <select name="country" id="country">

                        <option value="">
                            Select Country
                        </option>

                        @foreach($countries as $country)

                            <option
                                value="{{ $country }}"
                                {{ $selectedCountry === $country ? 'selected' : '' }}
                            >
                                {{ $country }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <button type="submit" class="btn btn-primary">
                    View Insights
                </button>

                <a
                    href="{{ route('geo.location-insights') }}"
                    class="btn btn-secondary"
                >
                    Reset
                </a>

            </form>

        </div>


        @if($selectedCountry)

            <div class="selected-country">

                Selected Country:

                <strong>
                    {{ $selectedCountry }}
                </strong>

            </div>


            {{-- Statistics --}}

            <div class="stats">

                <div class="stat-card">

                    <div class="icon">
                        👥
                    </div>

                    <h3>
                        {{ $totalVisitors }}
                    </h3>

                    <p>
                        Total Visitors
                    </p>

                </div>


                <div class="stat-card">

                    <div class="icon">
                        🌐
                    </div>

                    <h3>
                        {{ $uniqueIps }}
                    </h3>

                    <p>
                        Unique IP Addresses
                    </p>

                </div>


                <div class="stat-card">

                    <div class="icon">
                        🏙️
                    </div>

                    <h3>
                        {{ $totalCities }}
                    </h3>

                    <p>
                        Cities Detected
                    </p>

                </div>

            </div>


            <div class="grid">

                {{-- Top Cities --}}

                <div class="card">

                    <h2>
                        🏙️ Top Cities
                    </h2>

                    @if($topCities->count() > 0)

                        @php
                            $maxCityVisitors = $topCities->max('total');
                        @endphp

                        @foreach($topCities as $city)

                            @php
                                $percentage = $maxCityVisitors > 0
                                    ? ($city->total / $maxCityVisitors) * 100
                                    : 0;
                            @endphp

                            <div class="city-row">

                                <div class="city-header">

                                    <span class="city-name">
                                        {{ $city->city }}
                                    </span>

                                    <span class="city-count">
                                        {{ $city->total }} visitors
                                    </span>

                                </div>

                                <div class="bar-container">

                                    <div
                                        class="bar"
                                        style="width: {{ $percentage }}%;"
                                    ></div>

                                </div>

                            </div>

                        @endforeach

                    @else

                        <div class="empty">
                            No city data available.
                        </div>

                    @endif

                </div>


                {{-- Visitor Trend --}}

                <div class="card">

                    <h2>
                        📈 Visitor Activity - Last 7 Days
                    </h2>

                    <table class="trend-table">

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Visitors
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($visitorTrend as $day)

                                <tr>

                                    <td>
                                        {{ $day['date'] }}
                                    </td>

                                    <td class="trend-number">
                                        {{ $day['total'] }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Recent Visitors --}}

            <div class="card">

                <h2>
                    🕐 Recent Visitors from {{ $selectedCountry }}
                </h2>

                @if($recentVisitors->count() > 0)

                    <table class="recent-table">

                        <thead>

                            <tr>

                                <th>
                                    IP Address
                                </th>

                                <th>
                                    City
                                </th>

                                <th>
                                    Latitude
                                </th>

                                <th>
                                    Longitude
                                </th>

                                <th>
                                    Date
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($recentVisitors as $visitor)

                                <tr>

                                    <td>
                                        {{ $visitor->ip_address }}
                                    </td>

                                    <td>
                                        {{ $visitor->city ?? 'Not Available' }}
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

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="empty">
                        No visitors found for this country.
                    </div>

                @endif

            </div>

        @else

            <div class="card">

                <div class="empty">

                    <h2>
                        No GeoIP visitor data available
                    </h2>

                    <p>
                        Detect some visitor locations first to see country
                        and city insights.
                    </p>

                    <a
                        href="{{ route('geo.detect') }}"
                        class="btn btn-primary"
                    >
                        Detect Location
                    </a>

                </div>

            </div>

        @endif

    </div>

</body>

</html>