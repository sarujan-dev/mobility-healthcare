<x-guest-layout>
    <x-slot name="brand">
        <h1>Almost There!</h1>
        <p>Enter the code sent to your email along with your new password to complete the reset.</p>
        <div class="brand-features">
            <div class="f"><div class="dot">🔒</div> Your account stays secure</div>
            <div class="f"><div class="dot">✓</div> Instant password update</div>
        </div>
    </x-slot>

    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:56px; height:56px; background:#e0e7ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:24px;">📧</div>
        <h2 style="font-size:22px; font-weight:800; margin:0; color:#111827;">Enter Reset Code</h2>
        <p style="font-size:13px; color:#6b7280; margin-top:8px;">We've sent a 6-digit code to<br><strong>{{ $email }}</strong></p>
    </div>

    @if (session('status'))
        <div class="status-box">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset-otp.submit') }}">
        @csrf

        <div class="field">
            <label for="otp">Reset Code</label>
            <input id="otp" type="text" name="otp" maxlength="6" inputmode="numeric" placeholder="000000" required autofocus
                style="width:100%; text-align:center; letter-spacing:10px; font-size:22px; font-weight:700; border:1.5px solid #e5e7eb; border-radius:10px; padding:12px; background:#fafafa;">
        </div>

        <div class="field">
            <label for="password">New Password</label>
            <div class="input-wrap">
                <span class="icon">🔒</span>
                <input id="password" type="password" name="password" required placeholder="••••••••">
            </div>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm New Password</label>
            <div class="input-wrap">
                <span class="icon">🔒</span>
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn-submit">Reset Password</button>
    </form>

    <form method="POST" action="{{ route('password.reset-otp.resend') }}" style="text-align:center; margin-top:18px;">
        @csrf
        <button type="submit" style="background:none; border:none; color:#4f46e5; font-size:13px; font-weight:600; text-decoration:underline; cursor:pointer;">
            Didn't receive the code? Resend
        </button>
    </form>
</x-guest-layout>
