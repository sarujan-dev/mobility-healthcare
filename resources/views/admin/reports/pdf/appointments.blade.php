<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        p.date { color: #666; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #d97706; color: #fff; }
        tr:nth-child(even) { background: #f9fafb; }
    </style>
</head>
<body>
    <h1>Mobility Health Care System - Appointments Report</h1>
    <p class="date">Generated on: {{ now()->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appt)
                <tr>
                    <td>{{ $appt->patient->user->name ?? '-' }}</td>
                    <td>{{ $appt->doctor->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appt->time_slot)->format('h:i A') }}</td>
                    <td>{{ $appt->type }}</td>
                    <td>{{ $appt->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:16px;">Total Appointments: {{ $appointments->count() }}</p>
</body>
</html>
