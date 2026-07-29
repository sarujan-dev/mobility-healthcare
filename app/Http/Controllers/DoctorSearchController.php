<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user'])
            ->where('approval_status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('doctor_type')) {
            $query->where('doctor_type', $request->doctor_type);
        }

        if ($request->filled('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }

        $doctors = $query->paginate(9)->withQueryString();

        return view('doctors.index', compact('doctors'));
    }

    public function show(Doctor $doctor)
    {
        abort_if($doctor->approval_status !== 'approved', 404);

        $doctor->load(['user', 'schedules']);

        return view('doctors.show', compact('doctor'));
    }
}
