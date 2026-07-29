<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\EmergencyRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDoctors = Doctor::where('approval_status', 'approved')->count();
        $vpCount = Doctor::where('approval_status', 'approved')->where('doctor_type', 'VP')->count();
        $vogCount = Doctor::where('approval_status', 'approved')->where('doctor_type', 'VOG')->count();

        $totalPatients = Patient::count();

        $totalAppointmentsThisMonth = Appointment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $emergencyRequestsToday = EmergencyRequest::whereDate('created_at', now()->toDateString())->count();

        $availableNow = Doctor::where('approval_status', 'approved')->where('availability_status', 'Available')->count();

$availabilityBreakdown = [
    'Available' => Doctor::where('approval_status', 'approved')->where('availability_status', 'Available')->count(),
    'Not Available' => Doctor::where('approval_status', 'approved')->where('availability_status', 'Not Available')->count(),
];

$availableNow = $availabilityBreakdown['Available'];

        $recentAppointments = Appointment::with('doctor.user', 'patient.user')
            ->latest()
            ->take(5)
            ->get();

        $recentEmergencyRequests = EmergencyRequest::with('patient.user', 'doctor.user')
            ->latest()
            ->take(5)
            ->get();

        $pendingDoctorApprovals = Doctor::where('approval_status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalDoctors', 'vpCount', 'vogCount', 'totalPatients',
            'totalAppointmentsThisMonth', 'emergencyRequestsToday', 'availableNow',
            'availabilityBreakdown', 'recentAppointments', 'recentEmergencyRequests',
            'pendingDoctorApprovals'
        ));
    }
}
