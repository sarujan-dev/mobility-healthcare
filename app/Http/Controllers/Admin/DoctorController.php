<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Notifications\DoctorApprovalStatus;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user']);

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        $doctors = $query->latest()->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

public function show(Doctor $doctor)
{
    $doctor->load(['user', 'schedules']);

    return view('admin.doctors.show', compact('doctor'));
}
    public function approve(Doctor $doctor)
    {
        $doctor->update(['approval_status' => 'approved']);
        $doctor->user->update(['status' => 'active']);
        $doctor->user->notify(new DoctorApprovalStatus($doctor));

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor approved successfully.');
    }

    public function reject(Doctor $doctor)
    {
        $doctor->update(['approval_status' => 'rejected']);
        $doctor->user->update(['status' => 'blocked']);
        $doctor->user->notify(new DoctorApprovalStatus($doctor));

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor rejected.');
    }
}
