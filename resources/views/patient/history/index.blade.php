<x-app-layout>
<div style="padding:24px; max-width:900px; margin:0 auto;">
    <h1 style="font-size:20px; font-weight:700; margin-bottom:20px;">My Medical History</h1>

    @forelse($history as $visit)
        <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:14px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-weight:600; font-size:15px; margin:0;">{{ $visit->doctor->user->name ?? '-' }}</p>
                    <p style="font-size:12px; color:#6b7280; margin:3px 0 0;">{{ $visit->doctor->hospital->name ?? '-' }}</p>
                </div>
                <span style="font-size:12px; color:#9ca3af;">{{ \Carbon\Carbon::parse($visit->appointment_date)->format('d M Y') }}</span>
            </div>

            @if($visit->consultationNote && ($visit->consultationNote->diagnosis || $visit->consultationNote->prescription))
                <div style="margin-top:14px; padding-top:14px; border-top:1px solid #f1f1f4;">
                    @if($visit->consultationNote->diagnosis)
                        <p style="font-size:13px; margin:0 0 6px;"><strong>Diagnosis:</strong> {{ $visit->consultationNote->diagnosis }}</p>
                    @endif
                    @if($visit->consultationNote->prescription)
                        <p style="font-size:13px; margin:0;"><strong>Prescription:</strong> {{ $visit->consultationNote->prescription }}</p>
                    @endif
                </div>
            @else
                <p style="font-size:12px; color:#9ca3af; margin-top:12px;">No notes recorded for this visit.</p>
            @endif
        </div>
    @empty
        <div style="background:#fff; border-radius:12px; padding:40px; text-align:center; color:#9ca3af; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            No completed visits yet.
        </div>
    @endforelse

    <div style="margin-top:16px;">{{ $history->links() }}</div>
</div>
</x-app-layout>
