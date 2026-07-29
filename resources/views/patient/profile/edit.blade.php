<x-app-layout>
<div style="padding:24px; max-width:800px; margin:0 auto;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">My Profile</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px;">✓ {{ session('success') }}</div>
    @endif

    <!-- Profile Header -->
    <div style="background:linear-gradient(135deg, #4f46e5, #6366f1); border-radius:12px; padding:24px; margin-bottom:20px; color:#fff; display:flex; align-items:center; gap:20px;">
        <div style="width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:700;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div>
            <p style="font-size:20px; font-weight:700; margin:0;">{{ auth()->user()->name }}</p>
            <p style="font-size:13px; opacity:0.85; margin:4px 0 0;">{{ auth()->user()->email }}</p>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div style="background:#fff; border-radius:12px; padding:28px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('patient.profile.update') }}" id="profile-form">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Date of Birth</label>
                    <input type="date" name="dob" value="{{ $patient->dob }}"
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Gender</label>
                    <select name="gender" style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                        <option value="">-- Select --</option>
                        <option value="Male" {{ $patient->gender=='Male'?'selected':'' }}>Male</option>
                        <option value="Female" {{ $patient->gender=='Female'?'selected':'' }}>Female</option>
                        <option value="Other" {{ $patient->gender=='Other'?'selected':'' }}>Other</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Address</label>
                <textarea name="address" rows="2" style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;" placeholder="House No, Street, City">{{ $patient->address }}</textarea>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Location on Map</label>
                <p style="font-size:12px; color:#9ca3af; margin-bottom:10px;">Click "Use My Current Location" (browser will ask permission), or click/drag the marker on the map.</p>

                <button type="button" id="use-location-btn"
                    style="margin-bottom:12px; background:#4f46e5; color:#fff; padding:10px 20px; border-radius:8px; border:none; font-size:13px; font-weight:600; cursor:pointer;">
                    📍 Use My Current Location
                </button>
                <span id="location-status" style="font-size:13px; color:#6b7280; margin-left:10px;"></span>

                <div id="map" style="height:320px; border-radius:10px; border:1px solid #e5e7eb;"></div>

                <input type="hidden" name="lat" id="lat" value="{{ $patient->lat }}">
                <input type="hidden" name="lng" id="lng" value="{{ $patient->lng }}">
            </div>

            <button type="submit" style="background:#4f46e5; color:#fff; padding:12px 28px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                Save Profile
            </button>
        </form>
    </div>
</div>

<script>
    const defaultLat = {{ $patient->lat ?? 6.9271 }};
    const defaultLng = {{ $patient->lng ?? 79.8612 }};

    const map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    function updateLatLng(lat, lng) {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    }

    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        updateLatLng(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('use-location-btn').addEventListener('click', function () {
        const status = document.getElementById('location-status');
        if (!navigator.geolocation) {
            status.textContent = 'Geolocation is not supported by your browser.';
            return;
        }
        status.textContent = 'Requesting location permission...';
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
                updateLatLng(lat, lng);
                status.textContent = 'Location set successfully ✓';
            },
            function (error) {
                status.textContent = error.code === error.PERMISSION_DENIED
                    ? 'Location permission denied. Set it manually on the map.'
                    : 'Unable to retrieve location. Please set it manually.';
            }
        );
    });
</script>
</x-app-layout>
