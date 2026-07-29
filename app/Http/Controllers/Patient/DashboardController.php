<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        $recentAppointments = $patient
            ->appointments()
            ->with('doctor.user')
            ->orderByDesc('appointment_date')
            ->take(5)
            ->get();

        $totalAppointments = $patient->appointments()->count();
        $upcomingAppointments = $patient->appointments()
            ->where('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'Cancelled')
            ->count();

        return view('patient.dashboard', compact('patient', 'recentAppointments', 'totalAppointments', 'upcomingAppointments'));
    }
}
