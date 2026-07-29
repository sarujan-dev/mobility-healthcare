<x-app-layout>
<div style="padding:24px; max-width:1400px; margin:0 auto;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">My Doctor Profile</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px;">✓ {{ session('success') }}</div>
    @endif

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Profile Header -->
    <div style="background:linear-gradient(135deg, #4f46e5, #6366f1); border-radius:12px; padding:24px; margin-bottom:20px; color:#fff; display:flex; align-items:center; gap:20px;">
        <img src="{{ $doctor->profile_photo ? asset('storage/'.$doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=fff&color=4f46e5' }}"
             style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,0.4);">
        <div>
            <p style="font-size:20px; font-weight:700; margin:0;">{{ auth()->user()->name }}</p>
            <p style="font-size:13px; opacity:0.85; margin:4px 0 8px;">{{ $doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>
            <span style="font-size:12px; padding:4px 12px; border-radius:20px; font-weight:600;
                background:{{ $doctor->availability_status == 'Available' ? 'rgba(34,197,94,0.25)' : 'rgba(239,68,68,0.25)' }};">
                {{ $doctor->availability_status }}
            </span>
        </div>
    </div>

    <!-- Availability Status + Location Card -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:16px; font-weight:600; margin:0 0 16px;">Availability Status & Current Location</h2>
        <p style="font-size:13px; color:#6b7280; margin:0 0 16px;">When you set yourself as <strong>Available</strong>, your current location will be used to show you in the Emergency VOG Finder to nearby patients.</p>

        <form method="POST" action="{{ route('doctor.availability.update') }}" id="status-form">
            @csrf

            <div style="display:flex; gap:12px; margin-bottom:20px;">
                <button type="button" onclick="setStatus('Available')" id="btn-available"
                    style="flex:1; padding:14px; border-radius:10px; border:2px solid {{ $doctor->availability_status == 'Available' ? '#22c55e' : '#e5e7eb' }};
                    background:{{ $doctor->availability_status == 'Available' ? '#f0fdf4' : '#fff' }};
                    color:{{ $doctor->availability_status == 'Available' ? '#166534' : '#6b7280' }};
                    font-weight:600; font-size:14px; cursor:pointer;">
                    ✅ Available
                </button>
                <button type="button" onclick="setStatus('Not Available')" id="btn-unavailable"
                    style="flex:1; padding:14px; border-radius:10px; border:2px solid {{ $doctor->availability_status == 'Not Available' ? '#ef4444' : '#e5e7eb' }};
                    background:{{ $doctor->availability_status == 'Not Available' ? '#fef2f2' : '#fff' }};
                    color:{{ $doctor->availability_status == 'Not Available' ? '#b91c1c' : '#6b7280' }};
                    font-weight:600; font-size:14px; cursor:pointer;">
                    ❌ Not Available
                </button>
            </div>

            <input type="hidden" name="availability_status" id="availability_status" value="{{ $doctor->availability_status }}">
            <input type="hidden" name="current_lat" id="current_lat" value="{{ $doctor->current_lat }}">
            <input type="hidden" name="current_lng" id="current_lng" value="{{ $doctor->current_lng }}">

            <div id="location-section" style="{{ $doctor->availability_status == 'Available' ? '' : 'display:none;' }}">
                <p style="font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Your Current Location</p>
                <button type="button" onclick="getLocation()"
                    style="background:#4f46e5; color:#fff; padding:10px 20px; border-radius:8px; border:none; font-size:13px; font-weight:600; cursor:pointer; margin-bottom:12px;">
                    📍 Use My Current Location
                </button>
                <span id="loc-status" style="font-size:13px; color:#6b7280; margin-left:8px;"></span>
                <div id="location-map" style="height:280px; border-radius:10px; border:1px solid #e5e7eb; margin-top:10px; {{ ($doctor->current_lat && $doctor->current_lng) ? '' : 'display:none;' }}"></div>
            </div>

            <button type="submit" style="margin-top:16px; background:#4f46e5; color:#fff; padding:12px 28px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                Update Status
            </button>
        </form>
    </div>

    <!-- Profile Info Card -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:16px; font-weight:600; margin:0 0 20px;">Profile Information</h2>
        <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Profile Photo</label>
                <input type="file" name="profile_photo" style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px; font-size:13px; box-sizing:border-box;">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Doctor Type</label>
                    <select name="doctor_type" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px; box-sizing:border-box;">
                        <option value="VP" {{ $doctor->doctor_type=='VP'?'selected':'' }}>VP (General Physician)</option>
                        <option value="VOG" {{ $doctor->doctor_type=='VOG'?'selected':'' }}>VOG Specialist</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Qualifications</label>
                    <input type="text" name="qualifications" value="{{ $doctor->qualifications }}"
                        style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px; box-sizing:border-box;"
                        placeholder="MBBS, MD (Obst & Gynae), DGO, MRCOG">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">About</label>
                <textarea name="about" rows="4" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px; resize:vertical; box-sizing:border-box;">{{ $doctor->about }}</textarea>
            </div>

            <button type="submit" style="background:#4f46e5; color:#fff; padding:12px 28px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                Save Profile
            </button>
        </form>
    </div>

    <a href="{{ route('doctor.schedule.index') }}" style="display:inline-flex; align-items:center; gap:6px; color:#4f46e5; font-size:14px; font-weight:500; text-decoration:none;">
        📅 Manage Weekly Schedule →
    </a>
</div>

<script>
    let map, marker;

    function setStatus(status) {
        document.getElementById('availability_status').value = status;
        const locSection = document.getElementById('location-section');

        if (status === 'Available') {
            document.getElementById('btn-available').style.border = '2px solid #22c55e';
            document.getElementById('btn-available').style.background = '#f0fdf4';
            document.getElementById('btn-available').style.color = '#166534';
            document.getElementById('btn-unavailable').style.border = '2px solid #e5e7eb';
            document.getElementById('btn-unavailable').style.background = '#fff';
            document.getElementById('btn-unavailable').style.color = '#6b7280';
            locSection.style.display = 'block';
            getLocation();
        } else {
            document.getElementById('btn-unavailable').style.border = '2px solid #ef4444';
            document.getElementById('btn-unavailable').style.background = '#fef2f2';
            document.getElementById('btn-unavailable').style.color = '#b91c1c';
            document.getElementById('btn-available').style.border = '2px solid #e5e7eb';
            document.getElementById('btn-available').style.background = '#fff';
            document.getElementById('btn-available').style.color = '#6b7280';
            locSection.style.display = 'none';
            document.getElementById('current_lat').value = '';
            document.getElementById('current_lng').value = '';
        }
    }

    function getLocation() {
        const status = document.getElementById('loc-status');
        if (!navigator.geolocation) {
            status.textContent = 'Geolocation not supported.';
            return;
        }
        status.textContent = 'Getting your location...';
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                document.getElementById('current_lat').value = lat;
                document.getElementById('current_lng').value = lng;
                status.textContent = '✓ Location captured';
                showMap(lat, lng);
            },
            function() {
                status.textContent = 'Could not get location. Please allow location access.';
            }
        );
    }

    function showMap(lat, lng) {
        const mapDiv = document.getElementById('location-map');
        mapDiv.style.display = 'block';
        if (!map) {
            map = L.map('location-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                document.getElementById('current_lat').value = pos.lat;
                document.getElementById('current_lng').value = pos.lng;
            });
        } else {
            map.setView([lat, lng], 15);
            marker.setLatLng([lat, lng]);
        }
    }

    @if($doctor->current_lat && $doctor->current_lng)
        window.onload = function() {
            showMap({{ $doctor->current_lat }}, {{ $doctor->current_lng }});
        };
    @endif
</script>
</x-app-layout>
