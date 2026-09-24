<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>GeoIP Visitors</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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
            flex-wrap: wrap;
            gap: 15px;
        }

        .navbar h2 {
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .container-main {
            max-width: 1450px;
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
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
        }

        .stat-title {
            color: #777;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;
        }

        .filter-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
            margin: 25px 0;
        }

        .filters {
            display: grid;
            grid-template-columns:
                2fr
                1fr
                1fr
                1fr
                1fr
                1fr;
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
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn-custom {
            padding: 10px 17px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-search {
            background: #212529;
            color: white;
        }

        .btn-clear {
            background: #e9ecef;
            color: #333;
        }

        .btn-csv {
            background: #198754;
            color: white;
        }

        .btn-json {
            background: #6f42c1;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-custom:hover {
            opacity: .88;
        }

        .table-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
            overflow-x: auto;
        }

        .table-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .result-count {
            color: #666;
        }

        .export-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            white-space: nowrap;
        }

        th a {
            color: #333;
            text-decoration: none;
        }

        th a:hover {
            text-decoration: underline;
        }

        tr:hover {
            background: #fafafa;
        }

        .ip-badge {
            padding: 5px 9px;
            border-radius: 20px;
            background: #e9ecef;
            font-size: 12px;
        }

        .coordinates {
            font-size: 12px;
            color: #555;
        }

        .pagination-wrapper {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .alert-success-custom {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            padding: 13px 17px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .danger-zone {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .small-note {
            color: #777;
            font-size: 12px;
        }

        .checkbox-cell {
            width: 45px;
            text-align: center;
        }

        .checkbox-cell input {
            width: 17px;
            height: 17px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        @media (max-width: 1200px) {

            .filters {
                grid-template-columns: repeat(3, 1fr);
            }

        }

        @media (max-width: 800px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 600px) {

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .container-main {
                padding: 0 12px;
            }

        }

    </style>

</head>


<body>


<!-- Navbar -->

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


<div class="container-main">


    <!-- Header -->

    <div class="header">

        <h1>
            🔎 Visitor Management
        </h1>

        <p>
            Search, filter, sort, export and manage GeoIP visitor records.
        </p>

    </div>


    <!-- Success Message -->

    @if(session('success'))

        <div class="alert-success-custom">

            ✅ {{ session('success') }}

        </div>

    @endif


    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card">

            <div class="stat-title">
                Total Visitor Records
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
                Current Filter Results
            </div>

            <div class="stat-number">
                {{ $filteredVisitors }}
            </div>

        </div>

    </div>


    <!-- Filters -->

    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('geo.visitors') }}"
        >

            <div class="filters">


                <!-- Search -->

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


                <!-- Country -->

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


                <!-- City -->

                <div>

                    <label>
                        City
                    </label>

                    <select name="city">

                        <option value="">
                            All Cities
                        </option>

                        @foreach($cities as $city)

                            <option
                                value="{{ $city }}"
                                {{ request('city') == $city ? 'selected' : '' }}
                            >
                                {{ $city }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- From Date -->

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


                <!-- To Date -->

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


                <!-- Date Preset -->

                <div>

                    <label>
                        Date Preset
                    </label>

                    <select name="date_preset">

                        <option value="">
                            All Dates
                        </option>

                        <option
                            value="today"
                            {{ request('date_preset') == 'today' ? 'selected' : '' }}
                        >
                            Today
                        </option>

                        <option
                            value="7days"
                            {{ request('date_preset') == '7days' ? 'selected' : '' }}
                        >
                            Last 7 Days
                        </option>

                        <option
                            value="30days"
                            {{ request('date_preset') == '30days' ? 'selected' : '' }}
                        >
                            Last 30 Days
                        </option>

                    </select>

                </div>


            </div>


            <!-- Second row -->

            <div class="filters mt-3">


                <!-- Coordinates -->

                <div>

                    <label>
                        Coordinates
                    </label>

                    <select name="coordinate_status">

                        <option value="">
                            All Locations
                        </option>

                        <option
                            value="with"
                            {{ request('coordinate_status') == 'with' ? 'selected' : '' }}
                        >
                            With Coordinates
                        </option>

                        <option
                            value="without"
                            {{ request('coordinate_status') == 'without' ? 'selected' : '' }}
                        >
                            Without Coordinates
                        </option>

                    </select>

                </div>


                <!-- Unique IP -->

                <div>

                    <label>
                        IP Mode
                    </label>

                    <select name="unique_ip">

                        <option value="">
                            All Visitor Records
                        </option>

                        <option
                            value="1"
                            {{ request('unique_ip') == '1' ? 'selected' : '' }}
                        >
                            Unique IP Only
                        </option>

                    </select>

                </div>


                <!-- Records per page -->

                <div>

                    <label>
                        Records Per Page
                    </label>

                    <select
                        name="per_page"
                        onchange="this.form.submit()"
                    >

                        @foreach($perPageOptions as $option)

                            <option
                                value="{{ $option }}"
                                {{ $perPage == $option ? 'selected' : '' }}
                            >
                                {{ $option }}
                            </option>

                        @endforeach

                    </select>

                </div>


            </div>


            <!-- Filter buttons -->

            <div class="filter-buttons">

                <button
                    type="submit"
                    class="btn-custom btn-search"
                >
                    🔎 Apply Filters
                </button>


                <a
                    href="{{ route('geo.visitors') }}"
                    class="btn-custom btn-clear"
                >
                    ✖ Clear
                </a>


                <a
                    href="{{ route('geo.visitors.export.csv', request()->query()) }}"
                    class="btn-custom btn-csv"
                >
                    📄 Export CSV
                </a>


                <a
                    href="{{ route('geo.visitors.export.json', request()->query()) }}"
                    class="btn-custom btn-json"
                >
                    {} Export JSON
                </a>

            </div>

        </form>

    </div>


    <!-- Results -->

    <div class="table-card">


        <div class="table-top">


            <div class="result-count">

                Showing

                <strong>
                    {{ $visitors->firstItem() ?? 0 }}
                </strong>

                to

                <strong>
                    {{ $visitors->lastItem() ?? 0 }}
                </strong>

                of

                <strong>
                    {{ $visitors->total() }}
                </strong>

                records.

            </div>


            <div class="danger-zone">

                <span class="small-note">
                    Select records to delete
                </span>

            </div>


        </div>


        <!-- Bulk Delete -->

        <form
            method="POST"
            action="{{ route('geo.visitors.bulk-delete') }}"
            id="bulkDeleteForm"
        >

            @csrf

            @method('DELETE')


            <table>

                <thead>

                    <tr>


                        <!-- Select -->

                        <th class="checkbox-cell">

                            <input
                                type="checkbox"
                                id="selectAll"
                                title="Select All"
                            >

                        </th>


                        <!-- ID -->

                        <th>

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'id',
                                    'direction' => $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc'
                                ]) }}"
                            >

                                ID

                                @if($sort === 'id')

                                    {{ $direction === 'asc' ? '↑' : '↓' }}

                                @endif

                            </a>

                        </th>


                        <!-- IP -->

                        <th>

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'ip_address',
                                    'direction' => $sort === 'ip_address' && $direction === 'asc' ? 'desc' : 'asc'
                                ]) }}"
                            >

                                IP Address

                                @if($sort === 'ip_address')

                                    {{ $direction === 'asc' ? '↑' : '↓' }}

                                @endif

                            </a>

                        </th>


                        <!-- Country -->

                        <th>

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'country',
                                    'direction' => $sort === 'country' && $direction === 'asc' ? 'desc' : 'asc'
                                ]) }}"
                            >

                                Country

                                @if($sort === 'country')

                                    {{ $direction === 'asc' ? '↑' : '↓' }}

                                @endif

                            </a>

                        </th>


                        <!-- City -->

                        <th>

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'city',
                                    'direction' => $sort === 'city' && $direction === 'asc' ? 'desc' : 'asc'
                                ]) }}"
                            >

                                City

                                @if($sort === 'city')

                                    {{ $direction === 'asc' ? '↑' : '↓' }}

                                @endif

                            </a>

                        </th>


                        <th>
                            Latitude
                        </th>


                        <th>
                            Longitude
                        </th>


                        <!-- Date -->

                        <th>

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'created_at',
                                    'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc'
                                ]) }}"
                            >

                                Detected At

                                @if($sort === 'created_at')

                                    {{ $direction === 'asc' ? '↑' : '↓' }}

                                @endif

                            </a>

                        </th>


                        <th>
                            Action
                        </th>


                    </tr>

                </thead>


                <tbody>


                    @forelse($visitors as $visitor)


                        <tr>


                            <!-- Checkbox -->

                            <td class="checkbox-cell">

                                <input
                                    type="checkbox"
                                    name="visitor_ids[]"
                                    value="{{ $visitor->id }}"
                                    class="visitor-checkbox"
                                >

                            </td>


                            <!-- ID -->

                            <td>

                                <strong>
                                    {{ $visitor->id }}
                                </strong>

                            </td>


                            <!-- IP -->

                            <td>

                                <span class="ip-badge">
                                    {{ $visitor->ip_address }}
                                </span>

                            </td>


                            <!-- Country -->

                            <td>

                                {{ $visitor->country ?? 'N/A' }}

                            </td>


                            <!-- City -->

                            <td>

                                {{ $visitor->city ?? 'N/A' }}

                            </td>


                            <!-- Latitude -->

                            <td class="coordinates">

                                {{ $visitor->latitude ?? 'N/A' }}

                            </td>


                            <!-- Longitude -->

                            <td class="coordinates">

                                {{ $visitor->longitude ?? 'N/A' }}

                            </td>


                            <!-- Date -->

                            <td>

                                {{ $visitor->created_at->format('d M Y, h:i A') }}

                            </td>


                            <!-- Delete -->

                            <td>

                                <button
                                    type="button"
                                    class="btn-custom btn-delete"
                                    onclick="deleteVisitor({{ $visitor->id }})"
                                >
                                    🗑 Delete
                                </button>

                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="9"
                                class="empty"
                            >

                                <h4>
                                    No Visitors Found
                                </h4>

                                <p>
                                    Try changing your search or filters.
                                </p>

                            </td>

                        </tr>


                    @endforelse


                </tbody>

            </table>


            <!-- Bulk Delete Button -->

            @if($visitors->count() > 0)

                <div class="mt-3">

                    <button
                        type="submit"
                        class="btn-custom btn-delete"
                        onclick="return confirmBulkDelete()"
                    >
                        🗑 Delete Selected
                    </button>

                </div>

            @endif


        </form>


        <!-- Pagination -->

        <div class="pagination-wrapper">

            {{ $visitors
                ->onEachSide(1)
                ->links('pagination::bootstrap-5')
            }}

        </div>


    </div>


</div>


<!-- Single Delete Forms -->

@foreach($visitors as $visitor)

    <form
        method="POST"
        action="{{ route('geo.visitors.delete', $visitor) }}"
        id="delete-form-{{ $visitor->id }}"
        style="display:none;"
    >

        @csrf

        @method('DELETE')

    </form>

@endforeach


<script>

    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    const selectAll =
        document.getElementById('selectAll');

    const checkboxes =
        document.querySelectorAll('.visitor-checkbox');


    if (selectAll) {

        selectAll.addEventListener('change', function () {

            checkboxes.forEach(function (checkbox) {

                checkbox.checked =
                    selectAll.checked;

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Single Delete Confirmation
    |--------------------------------------------------------------------------
    */

    function deleteVisitor(id) {

        const confirmed = confirm(
            'Are you sure you want to delete this visitor record?'
        );

        if (confirmed) {

            document
                .getElementById('delete-form-' + id)
                .submit();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Confirmation
    |--------------------------------------------------------------------------
    */

    function confirmBulkDelete() {

        const selected =
            document.querySelectorAll(
                '.visitor-checkbox:checked'
            );

        if (selected.length === 0) {

            alert(
                'Please select at least one visitor record.'
            );

            return false;
        }

        return confirm(
            'Are you sure you want to delete ' +
            selected.length +
            ' selected visitor record(s)?'
        );

    }

</script>


</body>

</html>
