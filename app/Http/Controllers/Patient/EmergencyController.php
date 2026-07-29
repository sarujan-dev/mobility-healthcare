<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\EmergencyRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Notifications\EmergencyBookingAlert;

class EmergencyController extends Controller
{
    public function index()
    {
        return view('patient.emergency.index');
    }

public function find(Request $request)
{
    $request->validate([
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
    ]);

    $lat = $request->lat;
    $lng = $request->lng;

    // Use doctor's CURRENT location (current_lat, current_lng)
    $doctors = Doctor::select('doctors.*')
        ->selectRaw("
            (6371 * acos(
                cos(radians(?)) * cos(radians(doctors.current_lat)) *
                cos(radians(doctors.current_lng) - radians(?)) +
                sin(radians(?)) * sin(radians(doctors.current_lat))
            )) AS distance_km
        ", [$lat, $lng, $lat])
        ->where('doctors.doctor_type', 'VOG')
        ->where('doctors.availability_status', 'Available')
        ->where('doctors.approval_status', 'approved')
        ->whereNotNull('doctors.current_lat')
        ->whereNotNull('doctors.current_lng')
        ->with(['user'])
        ->orderBy('distance_km')
        ->limit(5)
        ->get();

    return response()->json([
        'doctors' => $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'photo' => $doctor->profile_photo
                    ? asset('storage/'.$doctor->profile_photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name),
                'distance_km' => round($doctor->distance_km, 1),
                'est_arrival_min' => max(1, round(($doctor->distance_km / 30) * 60)),
                'location_lat' => $doctor->current_lat,
                'location_lng' => $doctor->current_lng,
                'location_name' => 'Dr. ' . $doctor->user->name . ' (Current Location)',
                'location_address' => 'Live location',
            ];
        }),
    ]);
}

    public function book(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'distance_km' => 'nullable|numeric',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $patient = auth()->user()->patient;

        // Create an emergency appointment for today, current time slot
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->toDateString(),
            'time_slot' => now()->format('H:i'),
            'type' => 'Emergency',
            'status' => 'Pending',
            'notes' => 'Emergency VOG request - immediate attention needed.',
        ]);

        // Create emergency request record, linked to the appointment
        $emergencyRequest = EmergencyRequest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $appointment->id,
            'patient_lat' => $request->lat,
            'patient_lng' => $request->lng,
            'distance_km' => $request->distance_km,
            'status' => 'Pending',
        ]);

        $emergencyRequest->load('patient.user', 'doctor.user');
        $doctor->user->notify(new EmergencyBookingAlert($emergencyRequest));

        return response()->json([
            'success' => true,
            'message' => 'Emergency request sent to ' . $doctor->user->name . '. They will be notified immediately.',
        ]);
    }
}
