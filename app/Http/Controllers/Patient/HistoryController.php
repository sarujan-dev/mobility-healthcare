<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class HistoryController extends Controller
{
    public function index()
    {
        $history = Appointment::where('patient_id', auth()->user()->patient->id)
            ->where('status', 'Completed')
           ->with('doctor.user', 'consultationNote')
            ->orderByDesc('appointment_date')
            ->paginate(10);

        return view('patient.history.index', compact('history'));
    }
}
