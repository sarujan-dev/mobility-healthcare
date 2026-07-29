<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $patient = auth()->user()->patient;

        return view('patient.profile.edit', compact('patient'));
    }

    public function update(Request $request)
    {
        $patient = auth()->user()->patient;

        $request->validate([
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $patient->update($request->only(['dob', 'gender', 'address', 'lat', 'lng']));

        return back()->with('success', 'Profile updated successfully.');
    }
}
