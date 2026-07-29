<x-app-layout>
<div class="p-6 max-w-5xl mx-auto">

    <div style="background:#fee2e2; border:1px solid #ef4444; border-radius:12px; padding:20px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1 style="font-size:20px; font-weight:700; color:#b91c1c; margin:0;">Find Nearest Available VOG Now</h1>
            <p style="color:#b91c1c; font-size:14px; margin:4px 0 0;">For pregnant ladies and emergencies</p>
        </div>
        <span style="font-size:32px;">🚨</span>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div id="location-request" style="background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); border-radius:8px; padding:32px; text-align:center;">
        <p style="font-size:16px; color:#374151; margin-bottom:16px;">We need your current location to find the nearest available VOG doctor.</p>
        <button id="get-location-btn" style="background:#dc2626; color:#fff; padding:14px 32px; border-radius:8px; border:none; font-weight:600; font-size:16px; cursor:pointer;">
            📍 Use My Current Location
        </button>
        <p id="location-status" style="margin-top:12px; font-size:14px; color:#6b7280;"></p>
    </div>

    <div id="results-section" style="display:none;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px;">
            <div>
                <div id="map" style="height:400px; border-radius:8px;" class="border"></div>
            </div>
            <div id="doctor-list" style="display:flex; flex-direction:column; gap:12px;"></div>
        </div>
    </div>

    <div id="no-results" style="display:none; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); border-radius:8px; padding:32px; text-align:center; margin-top:20px;">
        <p style="color:#6b7280;">No available VOG doctors found nearby right now. Please try calling a hospital directly, or check back shortly.</p>
    </div>

</div>

<script>
    let map, patientMarker;
    const patientIcon = L.divIcon({ html: '📍', iconSize: [24,24], className: '' });
    const doctorIcon = L.divIcon({ html: '🏥', iconSize: [24,24], className: '' });

    document.getElementById('get-location-btn').addEventListener('click', function () {
        const statusEl = document.getElementById('location-status');

        if (!navigator.geolocation) {
            statusEl.textContent = 'Geolocation is not supported by your browser.';
            return;
        }

        statusEl.textContent = 'Requesting location permission...';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                statusEl.textContent = 'Location found. Searching for nearest VOG doctors...';
                findNearestDoctors(lat, lng);
            },
            function (error) {
                if (error.code === error.PERMISSION_DENIED) {
                    statusEl.textContent = 'Location permission denied. We cannot find nearby doctors without your location.';
                } else {
                    statusEl.textContent = 'Unable to retrieve your location. Please try again.';
                }
            }
        );
    });

    function findNearestDoctors(lat, lng) {
        fetch("{{ route('patient.emergency.find') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ lat: lat, lng: lng })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('location-request').style.display = 'none';

            if (!data.doctors || data.doctors.length === 0) {
                document.getElementById('no-results').style.display = 'block';
                return;
            }

            document.getElementById('results-section').style.display = 'block';
            renderMap(lat, lng, data.doctors);
            renderDoctorList(lat, lng, data.doctors);
        })
        .catch(() => {
            document.getElementById('location-status').textContent = 'Something went wrong. Please try again.';
        });
    }

    function renderMap(patientLat, patientLng, doctors) {
        map = L.map('map').setView([patientLat, patientLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        L.marker([patientLat, patientLng], { icon: patientIcon }).addTo(map).bindPopup('Your Location').openPopup();

        doctors.forEach(function (doc) {
            if (doc.hospital_lat && doc.hospital_lng) {
                L.marker([doc.hospital_lat, doc.hospital_lng], { icon: doctorIcon }).addTo(map)
                    .bindPopup(doc.name + '<br>' + doc.hospital_name + '<br>' + doc.distance_km + ' km away');
            }
        });
    }

    function renderDoctorList(patientLat, patientLng, doctors) {
        const container = document.getElementById('doctor-list');
        container.innerHTML = '';

        doctors.forEach(function (doc, index) {
            const card = document.createElement('div');
            card.style.cssText = 'background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); border-radius:8px; padding:16px;' + (index === 0 ? ' border:2px solid #22c55e;' : '');

            card.innerHTML = `
                ${index === 0 ? '<p style="color:#16a34a; font-size:12px; font-weight:600; margin:0 0 8px;">✓ NEAREST AVAILABLE</p>' : ''}
                <div style="display:flex; gap:12px;">
                    <img src="${doc.photo}" style="width:56px; height:56px; border-radius:50%; object-fit:cover;">
                    <div style="flex:1;">
                        <p style="font-weight:600; margin:0;">${doc.name}</p>
                        <p style="font-size:13px; color:#6b7280; margin:2px 0;">VOG Specialist</p>
                        <p style="font-size:13px; color:#6b7280; margin:2px 0;">${doc.hospital_name}</p>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:12px; font-size:13px; color:#374151;">
                    <span>📏 ${doc.distance_km} km away</span>
                    <span>⏱ ~${doc.est_arrival_min} mins</span>
                </div>
                <button onclick="bookEmergency(${doc.id}, ${patientLat}, ${patientLng}, ${doc.distance_km}, this)"
                    style="width:100%; margin-top:12px; background:#dc2626; color:#fff; padding:10px; border-radius:6px; border:none; font-weight:600; cursor:pointer;">
                    Book Emergency Now
                </button>
            `;
            container.appendChild(card);
        });
    }

    function bookEmergency(doctorId, lat, lng, distanceKm, btn) {
        btn.disabled = true;
        btn.textContent = 'Booking...';

        fetch("{{ route('patient.emergency.book') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ doctor_id: doctorId, lat: lat, lng: lng, distance_km: distanceKm })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.textContent = '✓ Booked!';
                btn.style.background = '#16a34a';
                alert(data.message);
                window.location.href = "{{ route('patient.appointments.index') }}";
            } else {
                btn.disabled = false;
                btn.textContent = 'Book Emergency Now';
                alert('Something went wrong. Please try again.');
            }
        });
    }
</script>
</x-app-layout>
