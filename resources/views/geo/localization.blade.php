<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geo-Smart Currency & Regional Localization Studio</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f9; color: #212529; }
        .navbar { background: #1e293b; padding: 14px 28px; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .navbar h2 { margin: 0; font-size: 18px; font-weight: 700; color: #38bdf8; }
        .nav-links { display: flex; gap: 12px; flex-wrap: wrap; }
        .nav-links a { color: #cbd5e1; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 13.5px; }
        .nav-links a:hover, .nav-links a.active { background: #334155; color: white; }
        .container { max-width: 1280px; margin: 30px auto; padding: 0 20px; }
        .header { background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header h1 { margin: 0 0 6px; font-size: 24px; }
        .header p { margin: 0; color: #64748b; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .card { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card h2 { margin-top: 0; margin-bottom: 15px; font-size: 17px; color: #1e293b; }
        .stat-title { color: #64748b; font-size: 13px; margin-bottom: 8px; font-weight: 600; }
        .stat-value { font-size: 26px; font-weight: bold; color: #0f172a; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 600; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        .btn { display: inline-block; padding: 10px 18px; border-radius: 6px; text-decoration: none; color: white; border: none; cursor: pointer; font-size: 14px; font-weight: 600; background: #3b82f6; }
        .btn:hover { background: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 11px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 13.5px; }
        th { background: #f8fafc; color: #475569; }
        .flag-img { width: 32px; height: 24px; border-radius: 4px; vertical-align: middle; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        @media (max-width: 900px) { .grid-4, .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>🚩 Geo-Localization Studio</h2>
        <div class="nav-links">
            <a href="{{ route('geo.dashboard') }}">Dashboard</a>
            <a href="{{ route('geo.detect') }}">Detect</a>
            <a href="{{ route('geo.visitors') }}">Visitors</a>
            <a href="{{ route('geo.map') }}">Map</a>
            <a href="{{ route('geo.location-insights') }}">Insights</a>
            <a href="{{ route('geo.firewall') }}">Firewall</a>
            <a href="{{ route('geo.heatmap') }}">Heatmap</a>
            <a href="{{ route('geo.localization') }}" class="active">Localization</a>
        </div>
    </div>

    <div class="container">

        <div class="header">
            <h1>🚩 Geo-Smart Currency, Timezone & Regional Localization Studio</h1>
            <p>Automatic detection of regional currency, timezone, live clock, international calling code, and multi-currency converter based on visitor IP.</p>
        </div>

        <!-- IP Inspector Input -->
        <div class="card" style="margin-bottom: 25px;">
            <h2>🔎 Inspect Regional Localization for Custom IP</h2>
            <form action="{{ route('geo.localization') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="ip" class="form-control" style="flex: 1; min-width: 250px;" value="{{ $ip }}" placeholder="Enter IP Address (e.g. 49.36.0.1, 8.8.8.8, 185.220.101.5)..." required>
                <button type="submit" class="btn">🌐 Inspect Localization</button>
            </form>
        </div>

        <!-- 4 Key Regional Metric Cards -->
        <div class="grid-4">

            <!-- Country & Flag -->
            <div class="card">
                <div class="stat-title">Detected Country & Flag</div>
                <div class="stat-value" style="font-size: 20px;">
                    <img src="https://flagcdn.com/32x24/{{ $iso }}.png" class="flag-img" alt="flag">
                    <span style="margin-left: 8px;">{{ $country }}</span>
                </div>
                <div style="font-size: 13px; color: #64748b; margin-top: 8px;">City: {{ $city }} (ISO: {{ strtoupper($iso) }})</div>
            </div>

            <!-- Currency -->
            <div class="card">
                <div class="stat-title">Regional Currency</div>
                <div class="stat-value" style="color: #059669;">
                    {{ $currencySymbol }} {{ $currencyCode }}
                </div>
                <div style="font-size: 13px; color: #64748b; margin-top: 8px;">{{ $currencyName }} (Rate: {{ $exchangeRate }}/USD)</div>
            </div>

            <!-- Timezone & Clock -->
            <div class="card">
                <div class="stat-title">Regional Timezone</div>
                <div class="stat-value" style="font-size: 18px; color: #2563eb;">
                    🕒 {{ $timezone }}
                </div>
                <div style="font-size: 12.5px; color: #64748b; margin-top: 8px; font-weight: 600;" id="liveClock">{{ $localTime }}</div>
            </div>

            <!-- Calling Code -->
            <div class="card">
                <div class="stat-title">International Dial Code</div>
                <div class="stat-value" style="color: #7c3aed;">
                    📞 {{ $callingCode }}
                </div>
                <div style="font-size: 13px; color: #64748b; margin-top: 8px;">Country Phone Prefix</div>
            </div>

        </div>

        <div class="grid-2">

            <!-- Live Currency Converter Tool -->
            <div class="card">
                <h2>💱 Live Multi-Currency Converter</h2>
                <div class="form-group">
                    <label>Amount in USD ($):</label>
                    <input type="number" id="usdAmount" class="form-control" value="100" min="1" step="1">
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 8px; margin-top: 15px;">
                    <div style="font-size: 13px; color: #64748b; margin-bottom: 5px;">Converted Local Value in <strong>{{ $country }}</strong>:</div>
                    <div style="font-size: 28px; font-weight: bold; color: #059669;" id="convertedResult">
                        {{ $currencySymbol }} {{ number_format(100 * $exchangeRate, 2) }}
                    </div>
                </div>
            </div>

            <!-- Global Currency Rates Matrix -->
            <div class="card">
                <h2>🌐 Global Currency Exchange Reference (Base USD)</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th>Code</th>
                            <th>Symbol</th>
                            <th>Rate vs $1 USD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencyMap as $code => $info)
                            <tr style="{{ $code === $currencyCode ? 'background: #ecfdf5; font-weight: bold;' : '' }}">
                                <td>{{ $info['name'] }}</td>
                                <td><code>{{ $code }}</code></td>
                                <td>{{ $info['symbol'] }}</td>
                                <td>{{ $info['symbol'] }} {{ number_format($info['rate'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exchangeRate = {{ (float) $exchangeRate }};
            const currencySymbol = "{{ $currencySymbol }}";
            const usdInput = document.getElementById('usdAmount');
            const resultDiv = document.getElementById('convertedResult');

            function calculateConversion() {
                const usd = parseFloat(usdInput.value) || 0;
                const localVal = (usd * exchangeRate).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                resultDiv.innerText = currencySymbol + ' ' + localVal;
            }

            usdInput.addEventListener('input', calculateConversion);
        });
    </script>
</body>
</html>
