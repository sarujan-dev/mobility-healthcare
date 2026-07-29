<x-app-layout>
<div style="padding:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="font-size:22px; font-weight:700; margin:0;">My Appointment History</h1>
        <a href="{{ route('doctors.index') }}" style="background:#4f46e5; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:14px; font-weight:600;">+ Book New Appointment</a>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">⚠️ {{ session('error') }}</div>
    @endif

    <div style="background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06); overflow:hidden;">
        <table style="width:100%; text-align:left; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb; border-bottom:2px solid #f1f1f4;">
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Appt. ID</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Doctor</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Date</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Available Time</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                    @php
                        $dayOfWeek = \Carbon\Carbon::parse($appt->appointment_date)->format('l');
                        $schedule = $appt->doctor->schedules->where('day_of_week', $dayOfWeek)->first();
                    @endphp
                    <tr style="border-bottom:1px solid #f1f1f4; transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='#fff'">
                        <td style="padding:14px 16px;">
                            <span style="font-size:12px; font-weight:700; color:#6b7280; background:#f3f4f6; padding:4px 10px; border-radius:6px;">
                                APT-{{ str_pad($appt->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;">
                            @if($appt->appointment_number)
                                <span style="display:inline-flex; width:30px; height:30px; background:#e0e7ff; color:#4f46e5; border-radius:50%; align-items:center; justify-content:center; font-weight:700; font-size:13px;">
                                    {{ $appt->appointment_number }}
                                </span>
                            @else
                                <span style="font-size:12px; color:#9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px;">
                            <p style="font-weight:600; font-size:14px; margin:0; color:#111827;">{{ $appt->doctor->user->name ?? '-' }}</p>
                            <p style="font-size:12px; color:#6b7280; margin:2px 0 0;">{{ $appt->doctor->doctor_type ?? '' }}</p>
                        </td>
                        <td style="padding:14px 16px;">
                            <p style="font-size:13px; font-weight:600; margin:0; color:#111827;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}</p>
                            <p style="font-size:11px; color:#6b7280; margin:2px 0 0;">{{ $dayOfWeek }}</p>
                        </td>
                        <td style="padding:14px 16px;">
                            @if($schedule && !$schedule->is_off && $schedule->start_time && $schedule->end_time)
                                <span style="font-size:13px; font-weight:500; color:#111827;">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} — {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </span>
                            @else
                                <span style="font-size:12px; color:#9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="font-size:12px; padding:5px 12px; border-radius:20px; font-weight:600;
                                {{ $appt->status=='Confirmed' ? 'background:#dcfce7; color:#166534;' : ($appt->status=='Pending' ? 'background:#fef3c7; color:#92400e;' : ($appt->status=='Cancelled' ? 'background:#fee2e2; color:#b91c1c;' : 'background:#f3f4f6; color:#6b7280;')) }}">
                                {{ $appt->status }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;">
                            @if(in_array($appt->status, ['Pending', 'Confirmed']))
                                <form method="POST" action="{{ route('patient.appointments.cancel', $appt->id) }}" onsubmit="return confirm('Cancel this appointment?');">
                                    @csrf
                                    <button type="submit" style="background:#fee2e2; color:#b91c1c; border:none; padding:7px 16px; border-radius:8px; font-size:12px; cursor:pointer; font-weight:600;">
                                        Cancel
                                    </button>
                                </form>
                            @else
                                <span style="font-size:12px; color:#9ca3af;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:60px; text-align:center; color:#9ca3af;">
                            <div style="font-size:32px; margin-bottom:12px;">📅</div>
                            <p style="font-size:15px; font-weight:600; color:#374151; margin:0 0 6px;">No appointments yet</p>
                            <p style="font-size:13px; margin:0 0 16px;">Book your first appointment with a doctor.</p>
                            <a href="{{ route('doctors.index') }}" style="background:#4f46e5; color:#fff; padding:10px 24px; border-radius:8px; text-decoration:none; font-size:14px; font-weight:600;">Find a Doctor</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $appointments->links() }}</div>
</div>
</x-app-layout>
