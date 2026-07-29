<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlmcRegistration;
use App\Models\Doctor;
use Illuminate\Http\Request;

class SlmcRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = SlmcRegistration::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('slmc_reg_no', 'like', '%' . $search . '%')
                  ->orWhere('registered_name', 'like', '%' . $search . '%');
            });
        }

        $registrations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.slmc.index', compact('registrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slmc_reg_no' => ['required', 'string', 'max:50', 'unique:slmc_registrations,slmc_reg_no', 'regex:/^SLMC-[0-9]{3,10}$/i'],
            'registered_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'doctor_type' => ['required', 'in:VP,VOG'],
        ], [
            'slmc_reg_no.regex' => 'Format must be like SLMC-12345.',
            'slmc_reg_no.unique' => 'This SLMC number already exists in the registry.',
            'registered_name.regex' => 'Name can only contain letters and spaces.',
        ]);

        SlmcRegistration::create($request->only(['slmc_reg_no', 'registered_name', 'doctor_type']));

        return back()->with('success', 'SLMC registration added successfully.');
    }

    public function destroy(SlmcRegistration $slmcRegistration)
    {
        if ($slmcRegistration->is_used) {
            return back()->with('error', 'Cannot delete — this SLMC number is already linked to a registered doctor.');
        }

        $slmcRegistration->delete();

        return back()->with('success', 'SLMC entry removed.');
    }
}
