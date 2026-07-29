<x-app-layout>
<div style="padding:24px; max-width:650px; margin:0 auto;">
    <a href="{{ route('doctors.show', $doctor->id) }}" style="color:#4f46e5; font-size:14px; text-decoration:none;">&larr; Back to Doctor Profile</a>

    <div style="background:#fff; border-radius:12px; padding:28px; margin-top:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">

        <!-- Doctor Info -->
        <div style="display:flex; align-items:center; gap:14px; margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid #f1f1f4;">
            <img src="{{ $doctor->profile_photo ? asset('storage/'.$doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=e0e7ff&color=4f46e5' }}"
                 style="width:60px; height:60px; border-radius:50%; object-fit:cover;">
            <div style="flex:1;">
                <p style="font-weight:700; font-size:16px; margin:0;">{{ $doctor->user->name }}</p>
                <p style="font-size:13px; color:#6b7280; margin:3px 0 0;">{{ $doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:11px; color:#6b7280; margin:0 0 2px;">Slots remaining today</p>
                <p style="font-size:22px; font-weight:800; color:{{ $remainingSlots > 5 ? '#16a34a' : '#dc2626' }}; margin:0;">
                    {{ $remainingSlots }}<span style="font-size:13px; font-weight:400; color:#6b7280;">/20</span>
                </p>
            </div>
        </div>

        @if(session('error'))
            <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('patient.appointments.store', $doctor->id) }}" id="booking-form">
            @csrf
            <input type="hidden" name="appointment_date" id="selected_date" value="{{ $selectedDate }}">

            <!-- Step 1: Select Date -->
            <div style="margin-bottom:24px;">
                <p style="font-weight:700; font-size:14px; color:#374151; margin:0 0 12px;">
                    <span style="display:inline-flex; width:22px; height:22px; background:#4f46e5; color:#fff; border-radius:50%; align-items:center; justify-content:center; font-size:11px; margin-right:8px;">1</span>
                    Select Appointment Date
                </p>
                <div style="display:flex; gap:8px; overflow-x:auto; padding-bottom:6px;">
                    @forelse($availableDates as $date)
                        @php
                            $count = \App\Models\Appointment::where('doctor_id', $doctor->id)
                                ->where('appointment_date', $date->toDateString())
                                ->where('status', '!=', 'Cancelled')
                                ->count();
                            $full = $count >= 20;
                            $isSelected = $selectedDate == $date->toDateString();
                        @endphp
                        <div onclick="{{ $full ? '' : "selectDate('" . $date->toDateString() . "')" }}"
                             style="cursor:{{ $full ? 'not-allowed' : 'pointer' }};
                             border:1.5px solid {{ $isSelected ? '#4f46e5' : ($full ? '#e5e7eb' : '#e5e7eb') }};
                             background:{{ $isSelected ? '#4f46e5' : ($full ? '#f9fafb' : '#fff') }};
                             color:{{ $isSelected ? '#fff' : ($full ? '#9ca3af' : '#374151') }};
                             border-radius:10px; padding:10px 14px; text-align:center; min-width:68px; flex-shrink:0;">
                            <div style="font-size:11px; font-weight:500;">{{ $date->format('D') }}</div>
                            <div style="font-size:15px; font-weight:700;">{{ $date->format('d') }}</div>
                            <div style="font-size:11px;">{{ $date->format('M') }}</div>
                            @if($full)
                                <div style="font-size:9px; color:#ef4444; margin-top:2px;">Full</div>
                            @else
                                <div style="font-size:9px; color:{{ $isSelected ? '#c7d2fe' : '#16a34a' }}; margin-top:2px;">{{ 20 - $count }} left</div>
                            @endif
                        </div>
                    @empty
                        <div style="background:#fee2e2; color:#b91c1c; padding:14px; border-radius:8px; font-size:13px; width:100%;">
                            ⚠️ No available days found. Please check with the hospital.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Selected Date Info -->
            @if($isPastStartTimeToday)
    <div style="background:#fee2e2; border:1px solid #fecaca; border-radius:10px; padding:14px 16px; margin-bottom:24px;">
        <p style="font-size:13px; color:#b91c1c; margin:0;">⏰ Today's booking window has closed — the doctor's hours already started at {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}. Please select another date.</p>
    </div>
@elseif(!$isDayOff && !$isFullyBooked && $schedule)
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:14px 16px; margin-bottom:24px;">
        <p style="font-size:13px; color:#166534; margin:0;">
            ✅ <strong>{{ $dayOfWeek }}, {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</strong><br>
            <span style="font-size:12px;">Doctor available: {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} — {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
        </p>
    </div>
@elseif($isDayOff)
    <div style="background:#fef3c7; border:1px solid #fde68a; border-radius:10px; padding:14px 16px; margin-bottom:24px;">
        <p style="font-size:13px; color:#92400e; margin:0;">📅 Doctor is not available on {{ $dayOfWeek }}s. Please select another date.</p>
    </div>
@elseif($isFullyBooked)
    <div style="background:#fee2e2; border:1px solid #fecaca; border-radius:10px; padding:14px 16px; margin-bottom:24px;">
        <p style="font-size:13px; color:#b91c1c; margin:0;">🚫 All 20 slots are filled for this day. Please select another date.</p>
    </div>
@endif

            <!-- Notes -->
            <div style="margin-bottom:24px;">
                <p style="font-weight:700; font-size:14px; color:#374151; margin:0 0 8px;">
                    <span style="display:inline-flex; width:22px; height:22px; background:#4f46e5; color:#fff; border-radius:50%; align-items:center; justify-content:center; font-size:11px; margin-right:8px;">2</span>
                    Notes <span style="font-weight:400; color:#9ca3af;">(Optional)</span>
                </p>
                <textarea name="notes" rows="3" placeholder="Any symptoms or notes for the doctor..."
                    style="width:100%; border:1.5px solid #e5e7eb; border-radius:10px; padding:12px 14px; font-size:13px; resize:vertical; font-family:inherit;"></textarea>
            </div>

@if(!$isDayOff && !$isFullyBooked && !$isPastStartTimeToday)
    <button type="submit" style="width:100%; background:linear-gradient(135deg,#4f46e5,#6366f1); color:#fff; padding:14px; border-radius:10px; border:none; font-weight:700; font-size:15px; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,0.3);">
        Confirm Booking
    </button>
@endif
        </form>
    </div>
</div>

<script>
    function selectDate(date) {
        document.getElementById('selected_date').value = date;
        window.location.href = "{{ route('patient.appointments.create', $doctor->id) }}?date=" + date;
    }
</script>
</x-app-layout>
