<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    const MAX_PATIENTS_PER_DAY = 20;

public function index()
{
    $appointments = auth()->user()->patient
        ->appointments()
        ->with('doctor.user', 'doctor.schedules')
        ->orderByDesc('appointment_date')
        ->orderBy('appointment_number')
        ->paginate(10);

    return view('patient.appointments.index', compact('appointments'));
}

    public function create(Doctor $doctor, Request $request)
    {
        abort_if($doctor->approval_status !== 'approved', 404);

        $selectedDate = $request->get('date', now()->toDateString());
        $dayOfWeek = Carbon::parse($selectedDate)->format('l');

        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();

       $isDayOff = !$schedule || $schedule->is_off || !$schedule->start_time || !$schedule->end_time;

// Also treat today as unavailable if the doctor's start time has already passed
$isPastStartTimeToday = false;
if (!$isDayOff && Carbon::parse($selectedDate)->isToday()) {
    $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
    if (now()->format('H:i:s') > $scheduleStart->format('H:i:s')) {
        $isPastStartTimeToday = true;
    }
}

        $todayBookingCount = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $selectedDate)
            ->where('status', '!=', 'Cancelled')
            ->count();

        $isFullyBooked = $todayBookingCount >= self::MAX_PATIENTS_PER_DAY;

        // Get available dates (next 14 days, only days doctor works)
$availableDates = collect(range(0, 13))->map(function ($i) {
    return now()->addDays($i);
})->filter(function ($date) use ($doctor) {
    $day = $date->format('l');
    $schedule = $doctor->schedules()->where('day_of_week', $day)->first();

    if (!$schedule || $schedule->is_off || !$schedule->start_time) {
        return false;
    }

    // If this date is TODAY, block it if current time has already passed the doctor's start time
    if ($date->isToday()) {
        $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
        $now = Carbon::now();
        if ($now->format('H:i:s') > $scheduleStart->format('H:i:s')) {
            return false;
        }
    }

    return true;
})->values();

return view('patient.appointments.create', [
    'doctor' => $doctor,
    'selectedDate' => $selectedDate,
    'availableDates' => $availableDates,
    'schedule' => $schedule,
    'dayOfWeek' => $dayOfWeek,
    'isDayOff' => $isDayOff,
    'isPastStartTimeToday' => $isPastStartTimeToday,
    'isFullyBooked' => $isFullyBooked,
    'todayBookingCount' => $todayBookingCount,
    'remainingSlots' => max(0, self::MAX_PATIENTS_PER_DAY - $todayBookingCount),
]);
    }

    public function store(Request $request, Doctor $doctor)
    {
        abort_if($doctor->approval_status !== 'approved', 404);

        $request->validate([
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $dayOfWeek = Carbon::parse($request->appointment_date)->format('l');
        $schedule = $doctor->schedules()->where('day_of_week', $dayOfWeek)->first();

        // Validate schedule exists for that day
// Validate schedule exists for that day
if (!$schedule || $schedule->is_off || !$schedule->start_time) {
    return back()->with('error', 'Doctor is not available on ' . $dayOfWeek . '.');
}

// Block booking today if the doctor's start time has already passed
if (Carbon::parse($request->appointment_date)->isToday()) {
    $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
    if (now()->format('H:i:s') > $scheduleStart->format('H:i:s')) {
        return back()->with('error', 'Today\'s booking window has closed (doctor\'s hours already started). Please select another date.');
    }
}

        // Check max patients per day
        $todayCount = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $request->appointment_date)
            ->where('status', '!=', 'Cancelled')
            ->count();

        if ($todayCount >= self::MAX_PATIENTS_PER_DAY) {
            return back()->with('error', 'Maximum booking limit (20 patients) reached for this day. Please select another date.');
        }

        // Prevent same patient booking same doctor on same date again
$alreadyBooked = Appointment::where('doctor_id', $doctor->id)
    ->where('patient_id', auth()->user()->patient->id)
    ->where('appointment_date', $request->appointment_date)
    ->where('status', '!=', 'Cancelled')
    ->exists();

if ($alreadyBooked) {
    return back()->with('error', 'You already have an appointment with this doctor on ' . \Carbon\Carbon::parse($request->appointment_date)->format('d M Y') . '. You cannot book another appointment on the same day.');
}

        // Appointment number = order of booking for that day
        $appointmentNumber = $todayCount + 1;

        // Auto-assign time slot based on appointment number + schedule start time
        $startTime = Carbon::parse($schedule->start_time);
        $assignedSlot = $startTime->addHours($appointmentNumber - 1)->format('H:i');

        Appointment::create([
            'patient_id' => auth()->user()->patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $assignedSlot,
            'type' => 'Regular',
            'status' => 'Pending',
            'notes' => $request->notes,
            'appointment_number' => $appointmentNumber,
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment #' . $appointmentNumber . ' booked successfully for ' . Carbon::parse($request->appointment_date)->format('d M Y') . ' at ' . Carbon::parse($assignedSlot)->format('h:i A') . '. You will be notified once confirmed.');
    }

    public function cancel(Appointment $appointment)
    {
        abort_if($appointment->patient_id !== auth()->user()->patient->id, 403);
        $appointment->update(['status' => 'Cancelled']);
        return back()->with('success', 'Appointment cancelled.');
    }
}
