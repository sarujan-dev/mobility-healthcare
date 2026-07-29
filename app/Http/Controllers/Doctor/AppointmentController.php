<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\EmergencyRequest;
use App\Notifications\AppointmentStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
public function index(Request $request)
{
    $query = auth()->user()->doctor
        ->appointments()
        ->with('patient.user');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $appointments = $query->orderByDesc('appointment_date')
        ->orderBy('appointment_number')
        ->paginate(15);

    // Load doctor schedules once for the view
    auth()->user()->doctor->load('schedules');

    return view('doctor.appointments.index', compact('appointments'));
}

    public function confirm(Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->user()->doctor->id, 403);

        $appointment->update([
            'status' => 'Confirmed',
            'qr_token' => Str::random(32),
        ]);

        EmergencyRequest::where('appointment_id', $appointment->id)->update(['status' => 'Accepted']);

        $appointment->load('patient.user', 'doctor.user');
        $appointment->patient->user->notify(new AppointmentStatusUpdated($appointment));

        return back()->with('success', 'Appointment confirmed. QR code sent to patient via email.');
    }

    public function cancel(Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->user()->doctor->id, 403);

        $appointment->update(['status' => 'Cancelled']);

        EmergencyRequest::where('appointment_id', $appointment->id)->update(['status' => 'Cancelled']);

        $appointment->load('patient.user', 'doctor.user');
        $appointment->patient->user->notify(new AppointmentStatusUpdated($appointment));

        return back()->with('success', 'Appointment cancelled.');
    }

    public function scan()
    {
        return view('doctor.appointments.scan');
    }

    public function completeScan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $appointment = Appointment::where('qr_token', $request->token)
            ->where('doctor_id', auth()->user()->doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Invalid QR code. This appointment does not belong to you.']);
        }

        if ($appointment->status === 'Completed') {
            return response()->json(['success' => false, 'message' => 'This appointment has already been marked as completed.']);
        }

        if ($appointment->status !== 'Confirmed') {
            return response()->json(['success' => false, 'message' => 'This appointment is not in a confirmed state.']);
        }

        $appointment->load('patient.user');
        $appointment->update(['status' => 'Completed']);

        EmergencyRequest::where('appointment_id', $appointment->id)->update(['status' => 'Completed']);

        $appointment->patient->user->notify(new AppointmentStatusUpdated($appointment));

        return response()->json([
            'success' => true,
            'message' => 'Checkup completed for ' . $appointment->patient->user->name . '.',
            'patient_name' => $appointment->patient->user->name,
            'date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y'),
        ]);
    }
}
