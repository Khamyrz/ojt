<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f6f7fb; margin: 0; }
        .container { max-width: 1200px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 24px; }
        h1 { margin: 0 0 16px; color: #1d3557; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #eef0f4; text-align: left; }
        th { background: #f1f5f9; color: #334155; font-weight: 600; }
        tr:hover { background: #fafafa; }
        .badge { padding: 4px 8px; border-radius: 999px; font-size: 12px; }
        .badge-success { background: #e8f5e9; color: #2e7d32; }
        .badge-failed { background: #fdecea; color: #c62828; }
        .btn { padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; background: #1d3557; color: #fff; }
        .btn:hover { background: #16304e; }
        .modal { display:none; position:fixed; inset:0; background: rgba(0,0,0,0.6); z-index:9999; }
        .modal-content { width: 95%; max-width: 900px; background:#fff; border-radius:12px; margin: 40px auto; padding: 16px; }
        #map { width: 100%; height: 420px; border-radius: 8px; }
        .modal-header { display:flex; align-items:center; justify-content: space-between; margin-bottom: 12px; }
        .close { cursor: pointer; font-size: 24px; color: #64748b; }
        .meta { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin: 12px 0; }
        .meta div { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:8px; font-size: 13px; }
        .muted { color:#64748b; font-size:12px; }
    </style>
</head>
<body>
    <div class="container">
        <div style="display:flex; align-items:center; justify-content: space-between; gap:16px; margin-bottom: 8px;">
            <h1 style="margin:0">Admin Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn" title="Logout" style="background:#dc2626">Logout</button>
            </form>
        </div>
        <p class="muted">Grouped by IP address. Shows emails used, success/failure counts, attempts, and last seen. Click View to see details and map.</p>
        <div style="overflow:auto">
            <table>
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Emails</th>
                        <th>Location</th>
                        <th>Total Attempts</th>
                        <th>Success</th>
                        <th>Failed</th>
                        <th>Last Attempt</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginAttempts as $row)
                        <tr>
                            <td>{{ $row->ip_address }}</td>
                            <td>{{ str_replace(',', ', ', $row->emails) }}</td>
                            <td>{{ $row->city }}, {{ $row->country }}</td>
                            <td>{{ $row->total_attempts }}</td>
                            <td><span class="badge badge-success">{{ $row->successful_attempts }}</span></td>
                            <td><span class="badge badge-failed">{{ $row->failed_attempts }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($row->last_attempt)->diffForHumans() }}</td>
                            <td><button class="btn" onclick="openMapModal('{{ $row->ip_address }}', {{ $row->latitude ?? 0 }}, {{ $row->longitude ?? 0 }}, '{{ $row->city }}', '{{ $row->country }}')">View</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="8">No login attempts recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="mapModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="mapTitle" style="margin:0">Visitor Location</h2>
                <span class="close" onclick="closeMapModal()">&times;</span>
            </div>
            <div class="meta">
                <div><b>IP:</b> <span id="metaIp"></span></div>
                <div><b>City:</b> <span id="metaCity"></span></div>
                <div><b>Country:</b> <span id="metaCountry"></span></div>
                <div><b>Coords:</b> <span id="metaCoords"></span></div>
            </div>
            <div id="map"></div>
            <div style="margin-top:12px">
                <h3 style="margin: 8px 0">Attempt History</h3>
                <div id="attempts" class="muted">Loading…</div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        let map, marker, tileLayer;
        function openMapModal(ip, lat, lng, city, country) {
            document.getElementById('mapModal').style.display = 'block';
            document.getElementById('metaIp').textContent = ip;
            document.getElementById('metaCity').textContent = city || 'Unknown';
            document.getElementById('metaCountry').textContent = country || 'Unknown';
            document.getElementById('metaCoords').textContent = `${lat || 0}, ${lng || 0}`;

            setTimeout(() => {
                if (!map) {
                    map = L.map('map', { zoomControl: true, attributionControl: false });
                }
                if (tileLayer) { tileLayer.remove(); }
                tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map);
                const center = [lat || 0, lng || 0];
                map.setView(center, (lat && lng) ? 12 : 2);
                if (marker) { marker.remove(); }
                marker = L.marker(center).addTo(map);
                marker.bindPopup(`<b>${city || 'Unknown'}, ${country || 'Unknown'}</b><br>IP: ${ip}`).openPopup();
                loadAttempts(ip);
            }, 50);
        }

        function closeMapModal() {
            document.getElementById('mapModal').style.display = 'none';
        }

        function loadAttempts(ip) {
            const target = document.getElementById('attempts');
            target.textContent = 'Loading…';
            fetch(`{{ url('/admin/ip-details') }}/${encodeURIComponent(ip)}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) { target.textContent = 'Failed to load.'; return; }
                    if (!Array.isArray(data.attempts) || data.attempts.length === 0) { target.textContent = 'No attempts.'; return; }
                    const rows = data.attempts.map(a => {
                        const when = new Date(a.created_at).toLocaleString();
                        return `<div style="padding:6px 8px; border-bottom:1px solid #e5e7eb">
                            <div><b>${a.email}</b> — ${a.success ? '<span class=\'badge badge-success\'>Success</span>' : '<span class=\'badge badge-failed\'>Failed</span>'}</div>
                            <div class="muted">${when} • ${a.reason || ''}</div>
                        </div>`;
                    }).join('');
                    target.innerHTML = rows;
                })
                .catch(() => target.textContent = 'Failed to load.');
        }
    </script>
</body>
</html>


