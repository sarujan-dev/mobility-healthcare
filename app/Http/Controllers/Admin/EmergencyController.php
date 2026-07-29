<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;

class EmergencyController extends Controller
{
    public function index()
    {
       $requests = EmergencyRequest::with(['patient.user', 'doctor.user'])
            ->latest()
            ->paginate(15);

        return view('admin.emergency.index', compact('requests'));
    }
}
