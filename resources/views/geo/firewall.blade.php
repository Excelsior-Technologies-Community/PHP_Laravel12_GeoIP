<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoIP Firewall & Access Control Studio</title>
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
        .alert-success { background: #d1e7dd; color: #0f5132; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .card { background: white; padding: 22px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card h2 { margin-top: 0; margin-bottom: 15px; font-size: 17px; color: #1e293b; }
        .status-pill { display: inline-block; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .status-active { background: #d1e7dd; color: #0f5132; }
        .status-inactive { background: #f8d7da; color: #842029; }
        .btn { display: inline-block; padding: 9px 15px; border-radius: 6px; text-decoration: none; color: white; border: none; cursor: pointer; font-size: 13px; font-weight: 600; }
        .btn-success { background: #10b981; }
        .btn-danger { background: #ef4444; }
        .btn-primary { background: #3b82f6; }
        .btn-dark { background: #334155; }
        .btn-sm { padding: 4px 8px; font-size: 11px; }
        .tag { display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; border: 1px solid #cbd5e1; padding: 5px 10px; border-radius: 20px; margin: 3px; font-size: 13px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 13px; font-weight: 600; }
        .form-control { width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 13px; }
        th { background: #f8fafc; color: #475569; }
        @media (max-width: 900px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>🛡️ GeoIP Firewall Studio</h2>
        <div class="nav-links">
            <a href="{{ route('geo.dashboard') }}">Dashboard</a>
            <a href="{{ route('geo.detect') }}">Detect</a>
            <a href="{{ route('geo.visitors') }}">Visitors</a>
            <a href="{{ route('geo.map') }}">Map</a>
            <a href="{{ route('geo.location-insights') }}">Insights</a>
            <a href="{{ route('geo.firewall') }}" class="active">Firewall</a>
            <a href="{{ route('geo.heatmap') }}">Heatmap</a>
            <a href="{{ route('geo.localization') }}">Localization</a>
        </div>
    </div>

    <div class="container">

        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        <div class="header">
            <h1>🛡️ Real-Time GeoIP Firewall & Country Access Control Studio</h1>
            <p>Live country-based traffic filtering, dynamic IP blacklisting, whitelist enforcement, and security violation audit stream.</p>
        </div>

        <!-- Protection Banner Card -->
        <div class="card" style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <strong>Firewall Protection Status:</strong>
                    @if($enabled)
                        <span class="status-pill status-active" style="margin-left: 10px;">🟢 FIREWALL ACTIVE</span>
                    @else
                        <span class="status-pill status-inactive" style="margin-left: 10px;">🔴 FIREWALL DISABLED</span>
                    @endif
                    <div style="margin-top: 8px; color: #64748b; font-size: 13px;">
                        Active Protection Mode: <strong style="color: #1e293b;">{{ strtoupper($mode) }} MODE</strong>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <form action="{{ route('geo.firewall.toggle') }}" method="POST">
                        @csrf
                        @if($enabled)
                            <button type="submit" class="btn btn-danger">🔴 Disable Firewall</button>
                        @else
                            <button type="submit" class="btn btn-success">🟢 Enable Firewall Protection</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Firewall Mode Selector -->
        <div class="grid-2">
            <div class="card">
                <h2>⚙️ Protection Mode Configuration</h2>
                <form action="{{ route('geo.firewall.mode') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Select Protection Mode:</label>
                        <select name="mode" class="form-control" onchange="this.form.submit()">
                            <option value="blacklist" {{ $mode === 'blacklist' ? 'selected' : '' }}>🚫 Blacklist Mode (Block explicitly listed countries/IPs)</option>
                            <option value="whitelist" {{ $mode === 'whitelist' ? 'selected' : '' }}>✅ Whitelist Mode (Block ALL except whitelisted countries)</option>
                        </select>
                    </div>
                </form>
                <p style="font-size: 12px; color: #64748b; margin-bottom: 0;">
                    In <strong>Blacklist Mode</strong>, traffic is allowed by default except from blocked countries or IPs. In <strong>Whitelist Mode</strong>, only countries specified in the whitelist are allowed.
                </p>
            </div>

            <div class="card">
                <h2>➕ Add Country Blacklist Rule</h2>
                <form action="{{ route('geo.firewall.rule') }}" method="POST" style="display: flex; gap: 8px;">
                    @csrf
                    <input type="hidden" name="action" value="add_country">
                    <input type="text" name="value" class="form-control" placeholder="Enter Country Name (e.g. Russia, China, Brazil)..." required>
                    <button type="submit" class="btn btn-danger" style="white-space: nowrap;">+ Block Country</button>
                </form>
                <div style="margin-top: 12px;">
                    <span style="font-size: 12px; color: #64748b;">Quick Select from Detected Countries:</span>
                    <div style="margin-top: 6px;">
                        @foreach($allCountries->take(6) as $cName)
                            <form action="{{ route('geo.firewall.rule') }}" method="POST" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="action" value="add_country">
                                <input type="hidden" name="value" value="{{ $cName }}">
                                <button type="submit" class="btn btn-dark btn-sm" style="margin-bottom: 4px;">+ {{ $cName }}</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Rules Grid -->
        <div class="grid-3">

            <!-- Blacklisted Countries -->
            <div class="card">
                <h2>🚫 Blacklisted Countries ({{ count($blacklistedCountries) }})</h2>
                @if(count($blacklistedCountries))
                    @foreach($blacklistedCountries as $bCountry)
                        <div class="tag">
                            <span>🚫 {{ $bCountry }}</span>
                            <form action="{{ route('geo.firewall.rule') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="action" value="remove_country">
                                <input type="hidden" name="value" value="{{ $bCountry }}">
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: bold; padding: 0;">✕</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p style="font-size: 13px; color: #94a3b8;">No countries blacklisted yet.</p>
                @endif
            </div>

            <!-- Blacklisted IPs -->
            <div class="card">
                <h2>🌐 Blacklisted IPs ({{ count($blacklistedIps) }})</h2>
                <form action="{{ route('geo.firewall.rule') }}" method="POST" style="display: flex; gap: 6px; margin-bottom: 12px;">
                    @csrf
                    <input type="hidden" name="action" value="add_ip">
                    <input type="text" name="value" class="form-control" placeholder="Add IP (e.g. 192.168.1.1)..." required>
                    <button type="submit" class="btn btn-danger btn-sm">+ Add</button>
                </form>

                @if(count($blacklistedIps))
                    @foreach($blacklistedIps as $bIp)
                        <div class="tag">
                            <span>💻 {{ $bIp }}</span>
                            <form action="{{ route('geo.firewall.rule') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="action" value="remove_ip">
                                <input type="hidden" name="value" value="{{ $bIp }}">
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: bold; padding: 0;">✕</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p style="font-size: 13px; color: #94a3b8;">No individual IPs blacklisted.</p>
                @endif
            </div>

            <!-- Whitelisted Countries -->
            <div class="card">
                <h2>✅ Allowed Whitelist Countries ({{ count($whitelistedCountries) }})</h2>
                <form action="{{ route('geo.firewall.rule') }}" method="POST" style="display: flex; gap: 6px; margin-bottom: 12px;">
                    @csrf
                    <input type="hidden" name="action" value="add_whitelist_country">
                    <input type="text" name="value" class="form-control" placeholder="Allow Country..." required>
                    <button type="submit" class="btn btn-success btn-sm">+ Allow</button>
                </form>

                @if(count($whitelistedCountries))
                    @foreach($whitelistedCountries as $wCountry)
                        <div class="tag" style="background: #ecfdf5; border-color: #a7f3d0;">
                            <span style="color: #047857;">✅ {{ $wCountry }}</span>
                            <form action="{{ route('geo.firewall.rule') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="action" value="remove_whitelist_country">
                                <input type="hidden" name="value" value="{{ $wCountry }}">
                                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: bold; padding: 0;">✕</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p style="font-size: 13px; color: #94a3b8;">No whitelist entries.</p>
                @endif
            </div>

        </div>

        <!-- Blocked Security Audit Log -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">📋 Security Audit Stream: Blocked Access Logs ({{ count($blockedLogs) }})</h2>
                @if(count($blockedLogs))
                    <form action="{{ route('geo.firewall.clear-logs') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-sm" onclick="return confirm('Clear firewall logs?')">🗑 Clear Audit Logs</button>
                    </form>
                @endif
            </div>

            @if(count($blockedLogs))
                <table>
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>IP Address</th>
                            <th>Country / City</th>
                            <th>Block Reason</th>
                            <th>Target URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blockedLogs as $log)
                            <tr>
                                <td>{{ $log['timestamp'] }}</td>
                                <td><code>{{ $log['ip'] }}</code></td>
                                <td>{{ $log['country'] }} ({{ $log['city'] }})</td>
                                <td><span style="color: #ef4444; font-weight: 600;">{{ $log['reason'] }}</span></td>
                                <td style="font-family: monospace; font-size: 12px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $log['url'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #64748b; font-size: 13px; text-align: center; padding: 20px 0;">No security violations or blocked attempts logged yet.</p>
            @endif
        </div>

    </div>

</body>
</html>
