<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $totalDoctors = Doctor::where('approval_status', 'approved')->count();
        $totalPatients = Patient::count();

        return view('welcome', compact('totalDoctors', 'totalPatients'));
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($request->only(['name', 'email', 'message']));

        return back()->with('success', 'Thank you for reaching out! We will get back to you soon.');
    }
}
