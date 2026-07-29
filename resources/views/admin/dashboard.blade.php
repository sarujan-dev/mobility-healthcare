<x-app-layout>
<div style="padding:24px;">

    <!-- Stat Cards Row -->
    <div style="display:grid; grid-template-columns:repeat(5, 1fr); gap:16px; margin-bottom:24px;">
        <x-stat-card icon="👨‍⚕️" icon-bg="#e0e7ff" label="Total Doctors" value="{{ $totalDoctors }}" sublabel="VP: {{ $vpCount }} | VOG: {{ $vogCount }}" />
        <x-stat-card icon="👥" icon-bg="#dcfce7" label="Total Patients" value="{{ $totalPatients }}" sublabel="Registered Patients" />
        <x-stat-card icon="📅" icon-bg="#fef3c7" label="Total Appointments" value="{{ $totalAppointmentsThisMonth }}" sublabel="This Month" />
        <x-stat-card icon="🚨" icon-bg="#fee2e2" label="Emergency Requests" value="{{ $emergencyRequestsToday }}" sublabel="Today" />
        <x-stat-card icon="✅" icon-bg="#dbeafe" label="Available Now" value="{{ $availableNow }}" sublabel="Doctors Available" />
    </div>

    @if($pendingDoctorApprovals > 0)
        <div style="background:#fffbeb; border:1px solid #fbbf24; border-radius:8px; padding:14px 18px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#92400e; font-size:14px;">⚠️ {{ $pendingDoctorApprovals }} doctor registration(s) pending your approval.</span>
            <a href="{{ route('admin.doctors.index') }}" style="color:#92400e; font-weight:600; font-size:14px; text-decoration:underline;">Review Now</a>
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1.4fr 1fr 1fr; gap:20px;">

        <!-- Recent Appointments -->
        <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h2 style="font-size:15px; font-weight:600; margin:0;">Recent Appointments</h2>
            </div>
            <table style="width:100%; text-align:left; font-size:13px;">
                <thead>
                    <tr style="color:#9ca3af; border-bottom:1px solid #f1f1f4;">
                        <th style="padding:6px 0; font-weight:500;">Patient</th>
                        <th style="padding:6px 0; font-weight:500;">Doctor</th>
                        <th style="padding:6px 0; font-weight:500;">Status</th>
                        <td style="padding:8px 0;">
    <span style="display:inline-flex; width:26px; height:26px; background:#e0e7ff; color:#4f46e5; border-radius:50%; align-items:center; justify-content:center; font-weight:700; font-size:11px;">
        {{ $appt->appointment_number ?? '-' }}
    </span>
</td>
<td style="padding:8px 0;">{{ $appt->patient->user->name ?? '-' }}</td>
<td style="padding:8px 0; color:#4f46e5;">{{ $appt->doctor->user->name ?? '-' }}</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAppointments as $appt)
                        <tr style="border-bottom:1px solid #f1f1f4;">
                            <td style="padding:8px 0;">{{ $appt->patient->user->name ?? '-' }}</td>
                            <td style="padding:8px 0; color:#4f46e5;">{{ $appt->doctor->user->name ?? '-' }}</td>
                            <td style="padding:8px 0;">
                                <span style="font-size:11px; padding:3px 8px; border-radius:10px;
                                    {{ $appt->status=='Confirmed' ? 'background:#dcfce7; color:#16a34a;' : ($appt->status=='Pending' ? 'background:#fef3c7; color:#b45309;' : 'background:#fee2e2; color:#b91c1c;') }}">
                                    {{ $appt->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="padding:16px 0; text-align:center; color:#9ca3af;">No appointments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Emergency VOG Requests -->
        <div style="background:#fef2f2; border-radius:10px; padding:20px; border:1px solid #fecaca;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h2 style="font-size:15px; font-weight:600; margin:0; color:#b91c1c;">Emergency VOG Requests</h2>
                <a href="{{ route('admin.emergency.index') }}" style="font-size:12px; color:#b91c1c; text-decoration:underline;">View All</a>
            </div>
            @forelse($recentEmergencyRequests as $req)
                <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #fecaca;">
                    <div>
                        <p style="font-size:13px; font-weight:600; margin:0; color:#111827;">{{ $req->patient->user->name ?? '-' }}</p>
                        <p style="font-size:11px; color:#6b7280; margin:2px 0 0;">{{ $req->doctor->user->name ?? '-' }}</p>
                    </div>
                    <span style="font-size:11px; color:#b91c1c; align-self:center;">{{ $req->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p style="font-size:13px; color:#9ca3af; text-align:center; padding:20px 0;">No emergency requests yet.</p>
            @endforelse
        </div>

        <!-- Doctors Availability -->
        <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="font-size:15px; font-weight:600; margin:0 0 14px;">Doctors Availability</h2>
            <div style="display:flex; justify-content:center; margin-bottom:14px;">
                <canvas id="availabilityChart" width="160" height="160"></canvas>
            </div>
            <div style="display:flex; flex-direction:column; gap:8px; font-size:12px;">
@php
    $colors = ['Available' => '#22c55e', 'Not Available' => '#ef4444'];
@endphp
                @foreach($availabilityBreakdown as $status => $count)
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="display:flex; align-items:center; gap:6px;">
                            <span style="width:8px; height:8px; border-radius:50%; background:{{ $colors[$status] }};"></span>
                            {{ $status }}
                        </span>
                        <span style="font-weight:600;">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

   <div style="margin-top:20px; display:flex; gap:12px;">
    <a href="{{ route('admin.doctors.index') }}" style="background:#4f46e5; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:14px; font-weight:500;">Manage Doctors</a>
    <a href="{{ route('admin.emergency.index') }}" style="background:#dc2626; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:14px; font-weight:500;">Emergency Requests</a>
    <a href="{{ route('admin.slmc.index') }}" style="background:#0891b2; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:14px; font-weight:500;">SLMC Registry</a>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('availabilityChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($availabilityBreakdown)) !!},
            datasets: [{
                data: {!! json_encode(array_values($availabilityBreakdown)) !!},
                backgroundColor: ['#22c55e', '#ef4444'],
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            cutout: '65%',
        }
    });
</script>
</x-app-layout>
