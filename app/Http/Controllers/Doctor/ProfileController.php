<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $doctor = auth()->user()->doctor;
        return view('doctor.profile.edit', compact('doctor'));
    }

    public function update(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $request->validate([
            'doctor_type' => 'required|in:VP,VOG',
            'qualifications' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['doctor_type', 'qualifications', 'about']);

        if ($request->hasFile('profile_photo')) {
            if ($doctor->profile_photo) {
                Storage::disk('public')->delete($doctor->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('doctors', 'public');
        }

        $doctor->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateAvailability(Request $request)
    {
        $request->validate([
            'availability_status' => 'required|in:Available,Not Available',
            'current_lat' => 'nullable|numeric',
            'current_lng' => 'nullable|numeric',
        ]);

        $data = ['availability_status' => $request->availability_status];

        if ($request->availability_status === 'Available' && $request->current_lat && $request->current_lng) {
            $data['current_lat'] = $request->current_lat;
            $data['current_lng'] = $request->current_lng;
        } elseif ($request->availability_status === 'Not Available') {
            $data['current_lat'] = null;
            $data['current_lng'] = null;
        }

        auth()->user()->doctor->update($data);

        return back()->with('success', 'Status updated.');
    }
}
