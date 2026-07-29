<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ConsultationNote;
use Illuminate\Http\Request;

class PatientHistoryController extends Controller
{
    public function show(Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->user()->doctor->id, 403);

        $patient = $appointment->patient;

        $history = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', auth()->user()->doctor->id)
            ->where('status', 'Completed')
            ->with('consultationNote')
            ->orderByDesc('appointment_date')
            ->get();

        return view('doctor.patients.history', compact('patient', 'history', 'appointment'));
    }

    public function storeNote(Request $request, Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->user()->doctor->id, 403);

        $request->validate([
            'diagnosis' => 'nullable|string|max:1000',
            'prescription' => 'nullable|string|max:1000',
        ]);

        ConsultationNote::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'diagnosis' => $request->diagnosis,
                'prescription' => $request->prescription,
            ]
        );

        return back()->with('success', 'Consultation notes saved.');
    }
}
