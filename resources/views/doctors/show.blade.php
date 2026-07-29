<x-app-layout>
<div style="padding:24px; max-width:900px; margin:0 auto;">
    <a href="{{ route('doctors.index') }}" style="color:#4f46e5; font-size:14px; text-decoration:none; font-weight:500;">&larr; Back to Search</a>

    <!-- Header Card -->
    <div style="background:linear-gradient(135deg, #4f46e5, #6366f1); border-radius:14px; padding:28px; margin-top:16px; color:#fff; display:flex; align-items:center; gap:22px;">
        <img src="{{ $doctor->profile_photo ? asset('storage/'.$doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=fff&color=4f46e5&size=100' }}"
             style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,0.4); flex-shrink:0;">
        <div style="flex:1;">
            <p style="font-size:24px; font-weight:700; margin:0;">{{ $doctor->user->name }}</p>
            <p style="font-size:14px; opacity:0.85; margin:6px 0 10px;">{{ $doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>
            <span style="font-size:12px; padding:5px 14px; border-radius:20px; font-weight:600;
                background:{{ $doctor->availability_status == 'Available' ? 'rgba(34,197,94,0.25)' : 'rgba(239,68,68,0.25)' }};">
                {{ $doctor->availability_status }}
            </span>
        </div>
        <form method="POST" action="{{ route('patient.favorites.toggle', $doctor->id) }}">
            @csrf
            @php
                $isFav = \App\Models\Favorite::where('patient_id', auth()->user()->patient->id)->where('doctor_id', $doctor->id)->exists();
            @endphp
            <button type="submit" style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.4); color:#fff; border-radius:8px; padding:9px 18px; cursor:pointer; font-size:13px; font-weight:600; white-space:nowrap;">
                {{ $isFav ? '❤️ Saved' : '🤍 Save' }}
            </button>
        </form>
    </div>

    <!-- About & Qualifications Card -->
    <div style="background:#fff; border-radius:14px; padding:26px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-top:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
            @if($doctor->about)
                <div>
                    <p style="font-size:11px; color:#9ca3af; margin:0 0 6px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">About Doctor</p>
                    <p style="font-size:14px; color:#374151; margin:0; line-height:1.6;">{{ $doctor->about }}</p>
                </div>
            @endif
            @if($doctor->qualifications)
                <div>
                    <p style="font-size:11px; color:#9ca3af; margin:0 0 6px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">Qualifications</p>
                    <p style="font-size:14px; color:#374151; margin:0; line-height:1.6;">{{ $doctor->qualifications }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Current Location Card -->
    @if($doctor->current_lat && $doctor->current_lng)
        <div style="background:#fff; border-radius:14px; padding:26px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-top:20px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                <span style="font-size:18px;">📍</span>
                <h2 style="font-size:15px; font-weight:600; margin:0; color:#111827;">Current Location</h2>
            </div>
            <p style="font-size:13px; color:#6b7280; margin:0 0 16px;">This shows where the doctor is currently available. Location updates automatically when the doctor marks themselves as "Available".</p>
            <div id="doctor-location-map" style="height:300px; border-radius:10px; border:1px solid #e5e7eb;"></div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const map = L.map('doctor-location-map').setView([{{ $doctor->current_lat }}, {{ $doctor->current_lng }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([{{ $doctor->current_lat }}, {{ $doctor->current_lng }}]).addTo(map)
                .bindPopup("Dr. {{ $doctor->user->name }}").openPopup();
        </script>
    @else
        <div style="background:#f9fafb; border-radius:14px; padding:20px; margin-top:20px; text-align:center;">
            <p style="font-size:13px; color:#9ca3af; margin:0;">📍 Location not currently shared by this doctor.</p>
        </div>
    @endif

    <!-- Weekly Schedule Card -->
    <div style="background:#fff; border-radius:14px; padding:26px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-top:20px;">
        <h2 style="font-size:15px; font-weight:600; margin:0 0 16px; color:#111827;">Weekly Schedule</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(150px, 1fr)); gap:10px;">
            @php
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                $schedules = $doctor->schedules->keyBy('day_of_week');
            @endphp
            @foreach($days as $day)
                @php $s = $schedules[$day] ?? null; @endphp
                <div style="background:#f9fafb; border:1px solid #f1f1f4; border-radius:10px; padding:12px;">
                    <p style="font-size:12px; font-weight:600; color:#374151; margin:0 0 6px;">{{ $day }}</p>
                    @if(!$s || $s->is_off || !$s->start_time)
                        <p style="font-size:12px; color:#9ca3af; margin:0;">Closed</p>
                    @else
                        <p style="font-size:12px; color:#16a34a; margin:0; font-weight:500;">{{ \Carbon\Carbon::parse($s->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('h:i A') }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Book Appointment CTA -->
    <div style="margin-top:24px;">
        <a href="{{ route('patient.appointments.create', $doctor->id) }}" style="display:block; text-align:center; background:linear-gradient(135deg,#4f46e5,#6366f1); color:#fff; padding:16px; border-radius:12px; text-decoration:none; font-weight:700; font-size:16px; box-shadow:0 4px 14px rgba(79,70,229,0.3);">
            📅 Book Appointment
        </a>
    </div>
</div>
</x-app-layout>
