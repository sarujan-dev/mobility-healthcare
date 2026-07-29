<x-app-layout>
<div style="padding:24px; max-width:1100px; margin:0 auto;">
    <h1 style="font-size:22px; font-weight:700; margin:0 0 4px;">Welcome, {{ auth()->user()->name }}</h1>
    <p style="color:#6b7280; font-size:14px; margin:0 0 24px;">Here's an overview of your account.</p>

    <!-- Stat Cards -->
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <x-stat-card icon="📅" icon-bg="#e0e7ff" label="Total Appointments" value="{{ $totalAppointments }}" />
        <x-stat-card icon="⏰" icon-bg="#fef3c7" label="Upcoming Appointments" value="{{ $upcomingAppointments }}" />
        <div style="background:#fff; border-radius:10px; padding:18px; box-shadow:0 1px 3px rgba(0,0,0,0.06); display:flex; align-items:center; justify-content:center;">
            <a href="{{ route('patient.profile.edit') }}" style="color:#4f46e5; font-weight:600; font-size:14px; text-decoration:none;">Edit My Profile &rarr;</a>
        </div>
    </div>

    <!-- Emergency Banner -->
    <a href="{{ route('patient.emergency.index') }}" style="display:block; background:linear-gradient(135deg, #dc2626, #ef4444); color:#fff; border-radius:12px; padding:22px 24px; margin-bottom:16px; text-decoration:none;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <p style="font-weight:700; font-size:17px; margin:0;">🚨 Emergency VOG Finder</p>
                <p style="font-size:13px; margin:4px 0 0; opacity:0.9;">Find nearest available VOG doctor instantly</p>
            </div>
            <span style="font-size:22px;">&rarr;</span>
        </div>
    </a>

    <!-- Quick Actions -->
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <a href="{{ route('doctors.index') }}" style="background:#16a34a; color:#fff; border-radius:10px; padding:18px; text-decoration:none;">
            <p style="font-weight:600; font-size:15px; margin:0;">🔍 Find a Doctor</p>
            <p style="font-size:12px; margin:6px 0 0; opacity:0.9;">Search VP/VOG by specialization & location</p>
        </a>

        <a href="{{ route('patient.appointments.index') }}" style="background:#fff; border:1px solid #e5e7eb; color:#111827; border-radius:10px; padding:18px; text-decoration:none;">
            <p style="font-weight:600; font-size:15px; margin:0;">📋 My Appointments</p>
            <p style="font-size:12px; margin:6px 0 0; color:#6b7280;">View your full appointment history</p>
        </a>
    </div>

    <!-- Recent Appointments -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h2 style="font-size:16px; font-weight:600; margin:0;">Recent Appointments</h2>
            <a href="{{ route('patient.appointments.index') }}" style="color:#4f46e5; font-size:13px; text-decoration:none; font-weight:500;">View All</a>
        </div>

        <table style="width:100%; text-align:left; font-size:13px;">
            <thead>
                <tr style="color:#9ca3af; border-bottom:1px solid #f1f1f4;">
                    <th style="padding:8px 0; font-weight:500;">Doctor</th>
                    <th style="padding:8px 0; font-weight:500;">Date & Time</th>
                    <th style="padding:8px 0; font-weight:500;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAppointments as $appt)
                    <tr style="border-bottom:1px solid #f1f1f4;">
                        <td style="padding:10px 0; font-weight:500;">{{ $appt->doctor->user->name ?? '-' }}</td>
                        <td style="padding:10px 0; color:#6b7280;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}</td>
                        <td style="padding:10px 0;">
                            <span style="font-size:11px; padding:3px 10px; border-radius:12px;
                                {{ $appt->status=='Confirmed' ? 'background:#dcfce7; color:#166534;' : ($appt->status=='Pending' ? 'background:#fef3c7; color:#92400e;' : 'background:#fee2e2; color:#b91c1c;') }}">
                                {{ $appt->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="padding:24px 0; text-align:center; color:#9ca3af;">No appointments yet. Search for a doctor to get started.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
