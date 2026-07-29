<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SlmcRegistration;
use App\Models\Doctor;
use Illuminate\Http\Request;

class SlmcVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'slmc_reg_no' => 'required|string',
            'name' => 'nullable|string',
        ]);

        $slmcNo = trim($request->slmc_reg_no);

        $record = SlmcRegistration::where('slmc_reg_no', $slmcNo)->first();

        if (!$record) {
            return response()->json([
                'valid' => false,
                'message' => 'This SLMC registration number was not found in our records.',
            ]);
        }

        if ($record->is_used) {
            return response()->json([
                'valid' => false,
                'message' => 'This SLMC number is already registered with another account.',
            ]);
        }

        // Optional: check name similarity (not exact match required, just informative)
        if ($request->filled('name') && strtolower(trim($request->name)) !== strtolower(trim($record->registered_name))) {
            return response()->json([
                'valid' => false,
                'message' => 'The name entered does not match the name registered against this SLMC number ("' . $record->registered_name . '").',
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'SLMC number verified successfully (' . $record->doctor_type . ' - ' . $record->registered_name . ').',
            'doctor_type' => $record->doctor_type,
        ]);
    }
}
