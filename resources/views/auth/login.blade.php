<x-guest-layout>
    <x-slot name="brand">
        <h1>Welcome back to<br>Mobility Health Care</h1>
        <p>Log in to manage your appointments, find doctors, and access emergency support whenever you need it.</p>
        <div class="brand-features">
            <div class="f"><div class="dot">✓</div> Real-time doctor availability</div>
            <div class="f"><div class="dot">📅</div> Instant appointment booking</div>
            <div class="f"><div class="dot">🚨</div> Emergency VOG finder for mothers</div>
        </div>
        <div class="brand-help">
            <p>💡 <strong>New here?</strong> Click "Register" below the form to create your free account in under 2 minutes.</p>
        </div>
    </x-slot>

    <h2 style="font-size:24px; font-weight:800; text-align:center; color:#111827; margin:0;">Welcome Back</h2>
    <p style="text-align:center; color:#6b7280; font-size:14px; margin-top:6px; margin-bottom:32px;">Log in to your account to continue</p>

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

    <form method="POST" action="{{ route('login') }}" id="login-form" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <span class="icon">✉️</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
            </div>
            <p class="field-error" id="email-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <span class="icon">🔒</span>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            <p class="field-error" id="password-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
        </div>

        <div class="options-row">
            <label>
                <input type="checkbox" name="remember">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-submit">Log In</button>

        <p class="switch-text">Don't have an account? <a href="{{ route('register') }}">Register here — it's free</a></p>
    </form>

    <script>
        const loginForm = document.getElementById('login-form');
        loginForm.addEventListener('submit', function (e) {
            let valid = true;
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailErr = document.getElementById('email-error');
            const passErr = document.getElementById('password-error');

            emailErr.style.display = 'none';
            passErr.style.display = 'none';

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                emailErr.textContent = 'Please enter a valid email address.';
                emailErr.style.display = 'block';
                valid = false;
            }

            if (!password.value) {
                passErr.textContent = 'Password is required.';
                passErr.style.display = 'block';
                valid = false;
            }

            if (!valid) e.preventDefault();
        });
    </script>
</x-guest-layout>
