<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        p.date { color: #666; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #4f46e5; color: #fff; }
        tr:nth-child(even) { background: #f9fafb; }
    </style>
</head>
<body>
    <h1>Mobility Health Care System - Doctors Report</h1>
    <p class="date">Generated on: {{ now()->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Hospital</th>
                <th>Phone</th>
                <th>Availability</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->user->name }}</td>
                    <td>{{ $doctor->doctor_type }}</td>
                    <td>{{ $doctor->hospital->name ?? '-' }}</td>
                    <td>{{ $doctor->user->phone ?? '-' }}</td>
                    <td>{{ $doctor->availability_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:16px;">Total Doctors: {{ $doctors->count() }}</p>
</body>
</html>
