<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SlmcRegistration;
use App\Notifications\RegistrationOtp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:doctor,patient'],
            'phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/'],
            'doctor_type' => ['nullable', 'required_if:role,doctor', 'in:VP,VOG'],
            'slmc_reg_no' => [
                'nullable',
                'required_if:role,doctor',
                'string',
                'max:50',
                'exists:slmc_registrations,slmc_reg_no',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->role === 'doctor' && \App\Models\Doctor::where('slmc_reg_no', $value)->exists()) {
                        $fail('This SLMC number is already registered with another account.');
                    }
                },
            ],
        ], [
            'name.regex' => 'Name can only contain letters and spaces.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'phone.regex' => 'Phone number must start with 0 and contain only numbers (e.g., 0771234567).',
            'slmc_reg_no.exists' => 'This SLMC registration number was not found in our records.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'doctor_type' => $validated['doctor_type'] ?? null,
            'slmc_reg_no' => $validated['slmc_reg_no'] ?? null,
        ];

        $otp = random_int(100000, 999999);

        Cache::put('register_otp_' . $validated['email'], [
            'otp' => $otp,
            'payload' => $payload,
        ], now()->addMinutes(10));

        Notification::route('mail', $validated['email'])
            ->notify(new RegistrationOtp((string) $otp, $validated['name']));

        session(['otp_email' => $validated['email']]);

        return redirect()->route('register.verify-otp');
    }

    public function showOtpForm(): View|RedirectResponse
    {
        if (!session('otp_email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', ['email' => session('otp_email')]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('register');
        }

        $cached = Cache::get('register_otp_' . $email);

        if (!$cached) {
            return back()->withErrors(['otp' => 'Your verification code has expired. Please register again.']);
        }

        if ((string) $cached['otp'] !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid verification code. Please try again.']);
        }

        $data = $cached['payload'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'phone' => $data['phone'],
            'status' => $data['role'] === 'doctor' ? 'pending' : 'active',
            'email_verified_at' => now(),
        ]);

        if ($data['role'] === 'doctor') {
           $user->doctor()->create([
    'doctor_type' => $data['doctor_type'] ?? 'VP',
    'slmc_reg_no' => $data['slmc_reg_no'],
    'approval_status' => 'pending',
]);

\App\Models\SlmcRegistration::where('slmc_reg_no', $data['slmc_reg_no'])->update(['is_used' => true]);
        } else {
            $user->patient()->create([]);
        }

        Cache::forget('register_otp_' . $email);
        session()->forget('otp_email');

        event(new Registered($user));

        // Doctors must wait for admin approval before logging in
        if ($data['role'] === 'doctor') {
            return redirect()->route('login')->with('status', 'Registration successful! Your account is pending admin approval. You will be able to log in once approved.');
        }

        // Patients are logged in immediately
        Auth::login($user);

        return redirect('/dashboard');
    }

    public function resendOtp(): RedirectResponse
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('register');
        }

        $cached = Cache::get('register_otp_' . $email);

        if (!$cached) {
            return redirect()->route('register')->withErrors(['otp' => 'Your session has expired. Please register again.']);
        }

        $otp = random_int(100000, 999999);
        $cached['otp'] = $otp;
        Cache::put('register_otp_' . $email, $cached, now()->addMinutes(10));

        Notification::route('mail', $email)
            ->notify(new RegistrationOtp((string) $otp, $cached['payload']['name']));

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}
