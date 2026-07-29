<x-guest-layout>
    <x-slot name="brand">
        <h1>Almost There!</h1>
        <p>We've sent a 6-digit verification code to your email. Enter it to complete your registration.</p>
        <div class="brand-features">
            <div class="f"><div class="dot">🔒</div> Secure account verification</div>
            <div class="f"><div class="dot">⏱</div> Code expires in 10 minutes</div>
        </div>
    </x-slot>

    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:56px; height:56px; background:#e0e7ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:24px;">📧</div>
        <h2 style="font-size:22px; font-weight:800; margin:0; color:#111827;">Verify Your Email</h2>
        <p style="font-size:13px; color:#6b7280; margin-top:8px;">We've sent a 6-digit verification code to<br><strong>{{ $email }}</strong></p>
    </div>

    @if (session('status'))
        <div class="status-box">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.verify-otp.submit') }}">
        @csrf
        <input type="text" name="otp" maxlength="6" inputmode="numeric" placeholder="000000" required autofocus
            style="display:block; width:100%; text-align:center; letter-spacing:10px; font-size:24px; font-weight:700; border:1.5px solid #e5e7eb; border-radius:10px; padding:14px; margin-bottom:18px; background:#fafafa;">

        <button type="submit" class="btn-submit">Verify & Continue</button>
    </form>

    <form method="POST" action="{{ route('register.resend-otp') }}" style="text-align:center; margin-top:18px;">
        @csrf
        <button type="submit" style="background:none; border:none; color:#4f46e5; font-size:13px; font-weight:600; text-decoration:underline; cursor:pointer;">
            Didn't receive the code? Resend
        </button>
    </form>
</x-guest-layout>
