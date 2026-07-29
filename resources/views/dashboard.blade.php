<x-app-layout>
<div style="padding:24px; max-width:1100px; margin:0 auto;">
    <h1 style="font-size:22px; font-weight:700; margin:0 0 4px;">Welcome, Dr. {{ auth()->user()->name }}</h1>
    <p style="color:#6b7280; font-size:14px; margin:0 0 20px;">Here's your practice overview for today.</p>

    <!-- Emergency Alert Banner -->
    @if($activeEmergency)
        <div style="background:linear-gradient(135deg, #dc2626, #ef4444); color:#fff; border-radius:12px; padding:18px 24px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <p style="font-weight:700; font-size:15px; margin:0;">🚨 Active Emergency Request</p>
                <p style="font-size:13px; margin:4px 0 0; opacity:0.9;">{{ $activeEmergency->patient->user->name }} needs immediate attention — {{ $activeEmergency->created_at->diffForHumans() }}</p>
            </div>
            <a href="{{ route('doctor.appointments.index') }}" style="background:#fff; color:#b91c1c; padding:8px 18px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">Respond Now</a>
        </div>
    @endif

    <!-- Stat Cards -->
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px;">
        <x-stat-card icon="⏳" icon-bg="#fef3c7" label="Pending Requests" value="{{ $pendingCount }}" />
        <x-stat-card icon="✅" icon-bg="#dcfce7" label="Confirmed" value="{{ $confirmedCount }}" />
        <x-stat-card icon="📊" icon-bg="#e0e7ff" label="Completed This Month" value="{{ $completedThisMonth }}" />
        <x-stat-card icon="👥" icon-bg="#dbeafe" label="Patients Served" value="{{ $totalPatientsServed }}" />
    </div>

    <!-- Availability Quick Toggle -->
    <div style="background:#fff; border-radius:12px; padding:20px 24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <p style="font-size:13px; color:#6b7280; margin:0 0 6px;">Current Status</p>
            <span style="font-size:15px; font-weight:600;
                color:{{ $doctor->availability_status=='Available' ? '#16a34a' : ($doctor->availability_status=='In OPD' ? '#2563eb' : '#6b7280') }};">
                ● {{ $doctor->availability_status }}
            </span>
        </div>
        <form method="POST" action="{{ route('doctor.availability.update') }}">
            @csrf
            <select name="availability_status" onchange="this.form.submit()" style="border:1px solid #d1d5db; border-radius:8px; padding:8px 12px; font-size:13px;">
                @foreach(['Available','Busy','In OPD','On Leave','Off Duty'] as $status)
                    <option value="{{ $status }}" {{ $doctor->availability_status==$status?'selected':'' }}>{{ $status }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:20px; margin-bottom:20px;">

        <!-- Today's Schedule -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h2 style="font-size:16px; font-weight:600; margin:0;">Today's Schedule ({{ now()->format('d M Y') }})</h2>
                <a href="{{ route('doctor.appointments.index') }}" style="color:#4f46e5; font-size:13px; text-decoration:none; font-weight:500;">View All</a>
            </div>

            <table style="width:100%; text-align:left; font-size:13px;">
                <thead>
                    <tr style="color:#9ca3af; border-bottom:1px solid #f1f1f4;">
                        <th style="padding:8px 0; font-weight:500;">Time</th>
                        <th style="padding:8px 0; font-weight:500;">Patient</th>
                        <th style="padding:8px 0; font-weight:500;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayAppointments as $appt)
                        <tr style="border-bottom:1px solid #f1f1f4;">
                            <td style="padding:10px 0; font-weight:600;">{{ \Carbon\Carbon::parse($appt->time_slot)->format('h:i A') }}</td>
                            <td style="padding:10px 0;">{{ $appt->patient->user->name ?? '-' }}</td>
                            <td style="padding:10px 0;">
                                <span style="font-size:11px; padding:3px 10px; border-radius:12px; {{ $appt->status=='Confirmed' ? 'background:#dcfce7; color:#166534;' : 'background:#fef3c7; color:#92400e;' }}">
                                    {{ $appt->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="padding:24px 0; text-align:center; color:#9ca3af;">No appointments scheduled for today.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Activity Feed -->
        <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="font-size:16px; font-weight:600; margin:0 0 16px;">Recent Activity</h2>
            @forelse($recentActivity as $act)
                <div style="display:flex; gap:10px; padding:10px 0; border-bottom:1px solid #f1f1f4;">
                    <span style="font-size:16px;">
                        {{ $act->status=='Completed' ? '✅' : ($act->status=='Confirmed' ? '👍' : ($act->status=='Cancelled' ? '❌' : '🕓')) }}
                    </span>
                    <div>
                        <p style="font-size:12px; margin:0;"><strong>{{ $act->patient->user->name ?? '-' }}</strong> — {{ $act->status }}</p>
                        <p style="font-size:11px; color:#9ca3af; margin:2px 0 0;">{{ $act->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p style="font-size:13px; color:#9ca3af;">No recent activity.</p>
            @endforelse
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:16px; font-weight:600; margin:0 0 16px;">Monthly Performance (Completed Appointments)</h2>
        <canvas id="performanceChart" height="70"></canvas>
    </div>

    <!-- Quick Actions -->
    <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;">
        <a href="{{ route('doctor.schedule.index') }}" style="background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px; text-decoration:none; color:#111827;">
            <p style="font-weight:600; font-size:14px; margin:0;">📅 Weekly Schedule</p>
            <p style="font-size:12px; margin:6px 0 0; color:#6b7280;">Manage your working hours</p>
        </a>
        <a href="{{ route('doctor.appointments.scan') }}" style="background:#4f46e5; color:#fff; border-radius:10px; padding:18px; text-decoration:none;">
            <p style="font-weight:600; font-size:14px; margin:0;">📷 Scan Patient QR</p>
            <p style="font-size:12px; margin:6px 0 0; opacity:0.9;">Complete a checkup</p>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('performanceChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($monthlyStats, 'label')) !!},
            datasets: [{
                label: 'Completed',
                data: {!! json_encode(array_column($monthlyStats, 'count')) !!},
                backgroundColor: '#4f46e5',
                borderRadius: 6,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
</x-app-layout>
