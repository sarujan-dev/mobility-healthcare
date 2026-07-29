<x-app-layout>
<div style="padding:24px;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">Reports & Analytics</h1>

    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:28px;">
        <x-stat-card icon="👨‍⚕️" icon-bg="#e0e7ff" label="Total Doctors" value="{{ $stats['total_doctors'] }}" />
        <x-stat-card icon="👥" icon-bg="#dcfce7" label="Total Patients" value="{{ $stats['total_patients'] }}" />
        <x-stat-card icon="📅" icon-bg="#fef3c7" label="Total Appointments" value="{{ $stats['total_appointments'] }}" />
        <x-stat-card icon="🚨" icon-bg="#fee2e2" label="Emergency Requests" value="{{ $stats['total_emergency'] }}" />
    </div>

    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">

        <!-- Doctors Report -->
        <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="font-size:15px; font-weight:600; margin:0 0 6px;">Doctors Report</h2>
            <p style="font-size:13px; color:#6b7280; margin:0 0 16px;">List of all approved doctors with specialization and hospital details.</p>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('admin.reports.doctors.pdf') }}" style="flex:1; text-align:center; background:#dc2626; color:#fff; padding:8px; border-radius:6px; text-decoration:none; font-size:13px;">📄 PDF</a>
                <a href="{{ route('admin.reports.doctors.excel') }}" style="flex:1; text-align:center; background:#16a34a; color:#fff; padding:8px; border-radius:6px; text-decoration:none; font-size:13px;">📊 Excel</a>
            </div>
        </div>

        <!-- Patients Report -->
        <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="font-size:15px; font-weight:600; margin:0 0 6px;">Patients Report</h2>
            <p style="font-size:13px; color:#6b7280; margin:0 0 16px;">List of all registered patients with contact information.</p>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('admin.reports.patients.pdf') }}" style="flex:1; text-align:center; background:#dc2626; color:#fff; padding:8px; border-radius:6px; text-decoration:none; font-size:13px;">📄 PDF</a>
                <a href="{{ route('admin.reports.patients.excel') }}" style="flex:1; text-align:center; background:#16a34a; color:#fff; padding:8px; border-radius:6px; text-decoration:none; font-size:13px;">📊 Excel</a>
            </div>
        </div>

        <!-- Appointments Report -->
        <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="font-size:15px; font-weight:600; margin:0 0 12px;">Appointments Report</h2>
            <form method="GET" id="appt-report-form" style="margin-bottom:12px;">
                <label style="font-size:12px; color:#6b7280;">From</label>
                <input type="date" name="from_date" style="display:block; width:100%; border:1px solid #d1d5db; border-radius:6px; padding:6px; margin-bottom:8px; font-size:13px;">
                <label style="font-size:12px; color:#6b7280;">To</label>
                <input type="date" name="to_date" style="display:block; width:100%; border:1px solid #d1d5db; border-radius:6px; padding:6px; font-size:13px;">
            </form>
            <div style="display:flex; gap:8px;">
                <button onclick="submitReport('pdf')" style="flex:1; text-align:center; background:#dc2626; color:#fff; padding:8px; border-radius:6px; border:none; font-size:13px; cursor:pointer;">📄 PDF</button>
                <button onclick="submitReport('excel')" style="flex:1; text-align:center; background:#16a34a; color:#fff; padding:8px; border-radius:6px; border:none; font-size:13px; cursor:pointer;">📊 Excel</button>
            </div>
        </div>

    </div>
</div>

<script>
    function submitReport(type) {
        const form = document.getElementById('appt-report-form');
        const fromDate = form.from_date.value;
        const toDate = form.to_date.value;
        let url = type === 'pdf' ? "{{ route('admin.reports.appointments.pdf') }}" : "{{ route('admin.reports.appointments.excel') }}";
        url += '?from_date=' + fromDate + '&to_date=' + toDate;
        window.location.href = url;
    }
</script>
</x-app-layout>
