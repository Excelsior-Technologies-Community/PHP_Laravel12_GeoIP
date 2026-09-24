<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Geo-Access Denied</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f172a;
            color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 40px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .icon-box {
            font-size: 64px;
            margin-bottom: 15px;
        }
        h1 {
            color: #ef4444;
            margin-top: 0;
            font-size: 28px;
        }
        p {
            color: #94a3b8;
            font-size: 15px;
            line-height: 1.6;
        }
        .meta-box {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 18px;
            margin: 25px 0;
            text-align: left;
        }
        .meta-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #1e293b;
            font-size: 14px;
        }
        .meta-item:last-child {
            border-bottom: none;
        }
        .label {
            color: #64748b;
            font-weight: 600;
        }
        .value {
            color: #f1f5f9;
            font-weight: 500;
        }
        .badge-danger {
            background: #7f1d1d;
            color: #fca5a5;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .btn-bypass {
            display: inline-block;
            margin-top: 15px;
            background: #3b82f6;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-bypass:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-box">🛡️⛔</div>
        <h1>403 Geo-Access Denied</h1>
        <p>Access from your geographical location or IP address has been restricted by the system's active GeoIP Firewall security rules.</p>

        <div class="meta-box">
            <div class="meta-item">
                <span class="label">Your IP Address:</span>
                <span class="value">{{ $ip ?? 'Unknown' }}</span>
            </div>
            <div class="meta-item">
                <span class="label">Detected Country:</span>
                <span class="value">
                    <img src="https://flagcdn.com/24x18/{{ strtolower($iso ?? 'us') }}.png" style="vertical-align: middle; margin-right: 5px;" alt="flag">
                    {{ $country ?? 'Unknown' }}
                </span>
            </div>
            <div class="meta-item">
                <span class="label">Detected City:</span>
                <span class="value">{{ $city ?? 'Unknown' }}</span>
            </div>
            <div class="meta-item">
                <span class="label">Security Rule:</span>
                <span class="badge-danger">FIREWALL BLOCKED</span>
            </div>
            <div class="meta-item" style="flex-direction: column; gap: 4px;">
                <span class="label">Reason:</span>
                <span class="value" style="color: #fca5a5; font-size: 13px;">{{ $reason ?? 'Geographical restriction enforced.' }}</span>
            </div>
        </div>

        <p style="font-size: 13px; color: #64748b;">If you believe this restriction is an error, please contact the site administrator or manage Firewall Studio settings.</p>

        <a href="/geo-firewall" class="btn-bypass">⚙️ Open Geo-Firewall Studio</a>
    </div>
</body>
</html>
