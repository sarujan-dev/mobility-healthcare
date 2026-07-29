<x-app-layout>
<div style="padding:24px; max-width:1000px; margin:0 auto;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">Doctor Verification Details</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px;">✓ {{ session('success') }}</div>
    @endif

    <!-- Header Card -->
    <div style="background:linear-gradient(135deg, #4f46e5, #6366f1); border-radius:12px; padding:28px; margin-bottom:20px; color:#fff; display:flex; align-items:center; gap:22px;">
        <img src="{{ $doctor->profile_photo ? asset('storage/'.$doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=fff&color=4f46e5&size=90' }}"
             style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,0.4); flex-shrink:0;">
        <div style="flex:1;">
            <p style="font-size:22px; font-weight:700; margin:0;">{{ $doctor->user->name }}</p>
            <p style="font-size:14px; opacity:0.85; margin:4px 0 0;">{{ $doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>
            <div style="display:flex; align-items:center; gap:16px; margin-top:10px;">
                <span style="font-size:13px; opacity:0.9;">✉️ {{ $doctor->user->email }}</span>
                <span style="font-size:13px; opacity:0.9;">📱 {{ $doctor->user->phone }}</span>
            </div>
        </div>
        <span style="font-size:12px; padding:6px 16px; border-radius:20px; font-weight:600; white-space:nowrap;
            background:{{ $doctor->approval_status == 'approved' ? 'rgba(34,197,94,0.25)' : ($doctor->approval_status=='pending' ? 'rgba(234,179,8,0.25)' : 'rgba(239,68,68,0.25)') }};">
            {{ ucfirst($doctor->approval_status) }}
        </span>
    </div>

    <!-- Credentials Card -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:15px; font-weight:600; margin:0 0 18px; color:#111827;">Medical Credentials</h2>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div style="display:flex; gap:12px; align-items:flex-start;">
                <div style="width:40px; height:40px; border-radius:10px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">🩺</div>
                <div>
                    <p style="font-size:11px; color:#9ca3af; margin:0 0 3px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">Doctor Type</p>
                    <p style="font-size:14px; font-weight:600; color:#111827; margin:0;">{{ $doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>
                </div>
            </div>

            <div style="display:flex; gap:12px; align-items:flex-start;">
                <div style="width:40px; height:40px; border-radius:10px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">🪪</div>
                <div>
                    <p style="font-size:11px; color:#9ca3af; margin:0 0 3px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">SLMC Registration No.</p>
                    <p style="font-size:14px; font-weight:600; color:#111827; margin:0;">
                        {{ $doctor->slmc_reg_no ?? '-' }}
                        <span style="display:inline-flex; align-items:center; gap:3px; font-size:11px; font-weight:600; color:#16a34a; margin-left:8px; background:#dcfce7; padding:2px 8px; border-radius:10px;">✓ Verified</span>
                    </p>
                </div>
            </div>

            <div style="display:flex; gap:12px; align-items:flex-start; grid-column:1 / -1;">
                <div style="width:40px; height:40px; border-radius:10px; background:#fef3c7; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">🎓</div>
                <div>
                    <p style="font-size:11px; color:#9ca3af; margin:0 0 3px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">Qualifications</p>
                    <p style="font-size:14px; color:#374151; margin:0;">{{ $doctor->qualifications ?: 'Not provided' }}</p>
                </div>
            </div>

            <div style="display:flex; gap:12px; align-items:flex-start; grid-column:1 / -1;">
                <div style="width:40px; height:40px; border-radius:10px; background:#f3e8ff; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">📝</div>
                <div>
                    <p style="font-size:11px; color:#9ca3af; margin:0 0 3px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">About</p>
                    <p style="font-size:14px; color:#374151; margin:0; line-height:1.6;">{{ $doctor->about ?: 'No description provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:15px; font-weight:600; margin:0 0 18px; color:#111827;">Status Overview</h2>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p style="font-size:11px; color:#9ca3af; margin:0 0 6px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">Availability</p>
                <span style="display:inline-block; font-size:13px; padding:5px 14px; border-radius:20px; font-weight:600;
                    {{ $doctor->availability_status == 'Available' ? 'background:#dcfce7; color:#166534;' : 'background:#fee2e2; color:#b91c1c;' }}">
                    {{ $doctor->availability_status }}
                </span>
            </div>
            <div>
                <p style="font-size:11px; color:#9ca3af; margin:0 0 6px; text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">Registered On</p>
                <p style="font-size:14px; font-weight:500; color:#111827; margin:0;">{{ $doctor->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule Card -->
    @if($doctor->schedules->count() > 0)
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
            <h2 style="font-size:15px; font-weight:600; margin:0 0 16px; color:#111827;">Weekly Schedule</h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(150px, 1fr)); gap:10px;">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    @php $sched = $doctor->schedules->firstWhere('day_of_week', $day); @endphp
                    <div style="background:#f9fafb; border:1px solid #f1f1f4; border-radius:10px; padding:12px;">
                        <p style="font-size:12px; font-weight:600; color:#374151; margin:0 0 6px;">{{ $day }}</p>
                        @if($sched && !$sched->is_off && $sched->start_time)
                            <p style="font-size:12px; color:#16a34a; margin:0; font-weight:500;">{{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}</p>
                        @else
                            <p style="font-size:12px; color:#9ca3af; margin:0;">Closed</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); display:flex; gap:12px;">
        @if($doctor->approval_status != 'approved')
            <form method="POST" action="{{ route('admin.doctors.approve', $doctor->id) }}">
                @csrf
                <button type="submit" style="background:#16a34a; color:#fff; padding:12px 28px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                    ✓ Approve Doctor
                </button>
            </form>
        @endif
        @if($doctor->approval_status != 'rejected')
            <form method="POST" action="{{ route('admin.doctors.reject', $doctor->id) }}">
                @csrf
                <button type="submit" style="background:#dc2626; color:#fff; padding:12px 28px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                    ✗ Reject Doctor
                </button>
            </form>
        @endif
    </div>

    <div style="margin-top:20px;">
        <a href="{{ route('admin.doctors.index') }}" style="color:#4f46e5; font-size:14px; text-decoration:none; font-weight:500;">&larr; Back to Doctor List</a>
    </div>
</div>
</x-app-layout>
