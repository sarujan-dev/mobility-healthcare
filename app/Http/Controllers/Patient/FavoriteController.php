<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('patient_id', auth()->user()->patient->id)
            ->with('doctor.user') 
            ->latest()
            ->get();

        return view('patient.favorites.index', compact('favorites'));
    }

    public function toggle(Doctor $doctor)
    {
        $patientId = auth()->user()->patient->id;

        $existing = Favorite::where('patient_id', $patientId)->where('doctor_id', $doctor->id)->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
        } else {
            Favorite::create(['patient_id' => $patientId, 'doctor_id' => $doctor->id]);
            $isFavorited = true;
        }

        if ($request = request()->ajax()) {
            return response()->json(['favorited' => $isFavorited]);
        }

        return back();
    }
}
