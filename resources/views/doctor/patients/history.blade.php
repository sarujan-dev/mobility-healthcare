<x-app-layout>
<div style="padding:24px; max-width:800px; margin:0 auto;">
    <a href="{{ route('doctor.appointments.index') }}" style="color:#4f46e5; font-size:14px; text-decoration:none;">&larr; Back to Appointments</a>

    <h1 style="font-size:20px; font-weight:700; margin:12px 0 4px;">{{ $patient->user->name }}'s Medical History</h1>
    <p style="color:#6b7280; font-size:13px; margin:0 0 20px;">{{ $patient->user->email }} | DOB: {{ $patient->dob ?? '-' }}</p>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:10px 14px; border-radius:8px; margin-bottom:16px; font-size:13px;">✓ {{ session('success') }}</div>
    @endif

    <!-- Add note for current appointment -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:15px; font-weight:600; margin:0 0 14px;">Add Consultation Note ({{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }})</h2>
        <form method="POST" action="{{ route('doctor.patients.note', $appointment->id) }}">
            @csrf
            <div style="margin-bottom:14px;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Diagnosis</label>
                <textarea name="diagnosis" rows="2" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px;">{{ $appointment->consultationNote->diagnosis ?? '' }}</textarea>
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Prescription</label>
                <textarea name="prescription" rows="2" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px;">{{ $appointment->consultationNote->prescription ?? '' }}</textarea>
            </div>
            <button type="submit" style="background:#4f46e5; color:#fff; padding:10px 24px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">Save Note</button>
        </form>
    </div>

    <!-- Past visits -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <h2 style="font-size:15px; font-weight:600; margin:0 0 14px;">Past Visits with You ({{ $history->count() }})</h2>
        @forelse($history as $visit)
            <div style="border-bottom:1px solid #f1f1f4; padding:14px 0;">
                <p style="font-size:13px; font-weight:600; margin:0;">{{ \Carbon\Carbon::parse($visit->appointment_date)->format('d M Y') }}</p>
                @if($visit->consultationNote)
                    <p style="font-size:13px; color:#374151; margin:4px 0;"><strong>Diagnosis:</strong> {{ $visit->consultationNote->diagnosis ?: '-' }}</p>
                    <p style="font-size:13px; color:#374151; margin:4px 0;"><strong>Prescription:</strong> {{ $visit->consultationNote->prescription ?: '-' }}</p>
                @else
                    <p style="font-size:13px; color:#9ca3af; margin:4px 0;">No notes recorded.</p>
                @endif
            </div>
        @empty
            <p style="font-size:13px; color:#9ca3af;">No past visits found.</p>
        @endforelse
    </div>
</div>
</x-app-layout>
