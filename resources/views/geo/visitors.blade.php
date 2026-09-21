<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>GeoIP Visitors</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #333;
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

        .header h1 {
            margin-bottom: 5px;
        }

        .header p {
            color: #777;
        }

        .filter-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            margin: 25px 0;
        }

        .filters {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
            gap: 12px;
            align-items: end;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
        }

        button,
        .clear-btn {
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        button {
            background: #212529;
            color: white;
        }

        .clear-btn {
            background: #e9ecef;
            color: #333;
        }

        .table-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        tr:hover {
            background: #fafafa;
        }

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            background: #e9ecef;
            font-size: 12px;
        }



        .result-count {
            margin-bottom: 15px;
            color: #666;
        }

        @media (max-width: 1000px) {

            .filters {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 600px) {

            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .filters {
                grid-template-columns: 1fr;
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

    </div>

</div>


<div class="container">


    <div class="header">

        <h1>🔎 Visitor Search & Filtering</h1>

        <p>
            Search and filter GeoIP visitor records.
        </p>

    </div>


    <!-- Filter -->

    <div class="filter-card">

        <form method="GET" action="{{ route('geo.visitors') }}">

            <div class="filters">


                <div>

                    <label>
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="IP, country or city..."
                    >

                </div>


                <div>

                    <label>
                        Country
                    </label>

                    <select name="country">

                        <option value="">
                            All Countries
                        </option>

                        @foreach($countries as $country)

                            <option
                                value="{{ $country }}"
                                {{ request('country') == $country ? 'selected' : '' }}
                            >
                                {{ $country }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div>

                    <label>
                        From Date
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >

                </div>


                <div>

                    <label>
                        To Date
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >

                </div>


                <div>

                    <button type="submit">
                        🔎 Search
                    </button>

                </div>


                <div>

                    <a
                        href="{{ route('geo.visitors') }}"
                        class="clear-btn"
                    >
                        Clear
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- Results -->

    <div class="table-card">

        <div class="result-count">

            Showing
            <strong>{{ $visitors->count() }}</strong>
            records on this page.

            Total matching records:
            <strong>{{ $visitors->total() }}</strong>

        </div>


        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>IP Address</th>

                    <th>Country</th>

                    <th>City</th>

                    <th>Latitude</th>

                    <th>Longitude</th>

                    <th>Detected At</th>

                </tr>

            </thead>


            <tbody>

                @forelse($visitors as $visitor)

                    <tr>

                        <td>
                            {{ $visitor->id }}
                        </td>

                        <td>
                            <span class="badge">
                                {{ $visitor->ip_address }}
                            </span>
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

                        <td colspan="7">
                            No visitors found matching your filters.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


<div class="pagination d-flex justify-content-center mt-4">

    {{ $visitors->onEachSide(1)->links('pagination::bootstrap-5') }}

</div>

    </div>

</div>


</body>

</html>