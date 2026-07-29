<x-guest-layout>
    <x-slot name="brand">
        <h1>Forgot Your<br>Password?</h1>
        <p>No worries. Enter your email and we'll send you a 6-digit code to reset your password securely.</p>
        <div class="brand-features">
            <div class="f"><div class="dot">🔒</div> Secure OTP verification</div>
            <div class="f"><div class="dot">⏱</div> Code expires in 10 minutes</div>
        </div>
    </x-slot>

    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:56px; height:56px; background:#e0e7ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:24px;">🔑</div>
        <h2 style="font-size:22px; font-weight:800; margin:0; color:#111827;">Reset Your Password</h2>
        <p style="font-size:13px; color:#6b7280; margin-top:8px;">Enter your email address and we'll send you a reset code.</p>
    </div>

    @if ($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <span class="icon">✉️</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
            </div>
        </div>

        <button type="submit" class="btn-submit">Send Reset Code</button>

        <p class="switch-text">Remembered your password? <a href="{{ route('login') }}">Log In</a></p>
    </form>
</x-guest-layout>
