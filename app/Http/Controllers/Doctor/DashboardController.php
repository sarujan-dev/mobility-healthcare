<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;

        $todayAppointments = $doctor->appointments()
            ->where('appointment_date', now()->toDateString())
            ->whereIn('status', ['Confirmed', 'Pending'])
            ->with('patient.user')
            ->orderBy('time_slot')
            ->get();

        $pendingCount = $doctor->appointments()->where('status', 'Pending')->count();
        $confirmedCount = $doctor->appointments()->where('status', 'Confirmed')->count();
        $completedThisMonth = $doctor->appointments()
            ->where('status', 'Completed')
            ->whereMonth('appointment_date', now()->month)
            ->count();
        $totalPatientsServed = $doctor->appointments()
            ->where('status', 'Completed')
            ->distinct('patient_id')
            ->count('patient_id');

        // Monthly performance chart - last 6 months
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats[] = [
                'label' => $month->format('M'),
                'count' => $doctor->appointments()
                    ->where('status', 'Completed')
                    ->whereMonth('appointment_date', $month->month)
                    ->whereYear('appointment_date', $month->year)
                    ->count(),
            ];
        }

        // Recent activity feed
        $recentActivity = $doctor->appointments()
            ->with('patient.user')
            ->orderByDesc('updated_at')
            ->take(6)
            ->get();

        // Active emergency requests
        $activeEmergency = $doctor->emergencyRequests()
            ->where('status', 'Pending')
            ->with('patient.user')
            ->latest()
            ->first();

        return view('doctor.dashboard', compact(
            'doctor', 'todayAppointments', 'pendingCount',
            'confirmedCount', 'completedThisMonth', 'totalPatientsServed',
            'monthlyStats', 'recentActivity', 'activeEmergency'
        ));
    }
}
