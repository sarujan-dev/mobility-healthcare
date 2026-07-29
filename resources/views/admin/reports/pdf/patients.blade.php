<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        p.date { color: #666; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #16a34a; color: #fff; }
        tr:nth-child(even) { background: #f9fafb; }
    </style>
</head>
<body>
    <h1>Mobility Health Care System - Patients Report</h1>
    <p class="date">Generated on: {{ now()->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Registered On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
                <tr>
                    <td>{{ $patient->user->name }}</td>
                    <td>{{ $patient->user->email }}</td>
                    <td>{{ $patient->user->phone ?? '-' }}</td>
                    <td>{{ $patient->gender ?? '-' }}</td>
                    <td>{{ $patient->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:16px;">Total Patients: {{ $patients->count() }}</p>
</body>
</html>
