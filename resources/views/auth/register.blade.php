<x-guest-layout>
    <x-slot name="brand">
        <h1 id="brand-title">Join Mobility<br>Health Care Today</h1>
        <p id="brand-desc">Create your account as a patient or a doctor and become part of Sri Lanka's most connected healthcare network.</p>
        <div class="brand-features" id="brand-features">
            <div class="f"><div class="dot">👥</div> Growing patient community</div>
            <div class="f"><div class="dot">👨‍⚕️</div> Verified VP & VOG doctors</div>
            <div class="f"><div class="dot">🔒</div> Secure OTP email verification</div>
        </div>
        <div class="brand-help">
            <p>💡 <strong>Not sure which to pick?</strong> If you want to book appointments with doctors, choose "Patient". If you are a licensed doctor, choose "Doctor".</p>
        </div>
    </x-slot>

    <h2 style="font-size:24px; font-weight:800; text-align:center; color:#111827; margin:0;">Create Your Account</h2>
    <p style="text-align:center; color:#6b7280; font-size:14px; margin-top:6px; margin-bottom:24px;">Join Mobility Health Care System</p>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Friendly Role Toggle -->
    <div class="role-toggle">
        <div class="role-toggle-btn {{ old('role') != 'doctor' ? 'active' : '' }}" id="toggle-patient" onclick="selectRole('patient')">
            <span class="icon">🧑</span>
            <span class="label">I'm a Patient</span>
        </div>
        <div class="role-toggle-btn {{ old('role') == 'doctor' ? 'active' : '' }}" id="toggle-doctor" onclick="selectRole('doctor')">
            <span class="icon">👨‍⚕️</span>
            <span class="label">I'm a Doctor</span>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
        @csrf
        <input type="hidden" name="role" id="role" value="{{ old('role', 'patient') }}">

        <div class="row-2">
            <div class="field">
                <label for="name">Full Name</label>
                <div class="input-wrap">
                    <span class="icon">👤</span>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        pattern="[A-Za-z\s]+" minlength="3" maxlength="100"
                        title="Only letters and spaces allowed"
                        autofocus autocomplete="name" placeholder="John Doe">
                </div>
                <p class="field-error" id="name-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
            </div>
            <div class="field">
                <label for="phone">Phone Number</label>
                <div class="input-wrap">
                    <span class="icon">📱</span>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                        inputmode="numeric" maxlength="10" pattern="0[0-9]{9}"
                        title="Must be exactly 10 digits starting with 0"
                        autocomplete="tel" placeholder="07XXXXXXXX">
                </div>
                <p class="field-error" id="phone-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
            </div>
        </div>

        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <span class="icon">✉️</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username" placeholder="you@example.com">
            </div>
            <p class="field-error" id="email-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
        </div>

        <div class="doctor-fields" id="doctor-fields" style="display: {{ old('role') == 'doctor' ? 'block' : 'none' }};">
            <div class="field no-icon">
                <label for="doctor_type">Doctor Type</label>
                <select id="doctor_type" name="doctor_type">
                    <option value="VP" {{ old('doctor_type')=='VP'?'selected':'' }}>VP (General Physician)</option>
                    <option value="VOG" {{ old('doctor_type')=='VOG'?'selected':'' }}>VOG Specialist</option>
                </select>
                <p class="helper-text">This will auto-select once your SLMC number is verified below.</p>
            </div>

            <div class="field no-icon">
                <label for="slmc_reg_no">SLMC Registration Number</label>
                <input id="slmc_reg_no" type="text" name="slmc_reg_no" value="{{ old('slmc_reg_no') }}"
                    placeholder="SLMC-12345" autocomplete="off">
                <p id="slmc-check-msg" style="font-size:12px; margin-top:6px; display:none;"></p>
                <p class="helper-text">Your SLMC number must be pre-registered by our admin team. Contact support if verification fails.</p>
            </div>
        </div>

        <div class="row-2">
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="icon">🔒</span>
                    <input id="password" type="password" name="password" required minlength="8"
                        autocomplete="new-password" placeholder="••••••••">
                </div>
                <p class="helper-text">At least 8 characters</p>
                <p class="field-error" id="password-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrap">
                    <span class="icon">🔒</span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                        autocomplete="new-password" placeholder="••••••••">
                </div>
                <p class="field-error" id="confirm-error" style="color:#ef4444; font-size:12px; margin-top:5px; display:none;"></p>
            </div>
        </div>

        <button type="submit" class="btn-submit">Create Account</button>

        <p class="switch-text">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
    </form>

    <script>
        function selectRole(role) {
            document.getElementById('role').value = role;
            document.getElementById('doctor-fields').style.display = (role === 'doctor') ? 'block' : 'none';

            document.getElementById('toggle-patient').classList.toggle('active', role === 'patient');
            document.getElementById('toggle-doctor').classList.toggle('active', role === 'doctor');

            const brandTitle = document.getElementById('brand-title');
            const brandDesc = document.getElementById('brand-desc');
            const brandFeatures = document.getElementById('brand-features');

            if (role === 'doctor') {
                brandTitle.innerHTML = 'Join as a<br>Verified Doctor';
                brandDesc.textContent = 'Manage your appointments, set your availability, and connect with patients across Sri Lanka.';
                brandFeatures.innerHTML = `
                    <div class="f"><div class="dot">🪪</div> SLMC verification required</div>
                    <div class="f"><div class="dot">📅</div> Set your own working hours</div>
                    <div class="f"><div class="dot">✅</div> Admin approval before login</div>
                `;
            } else {
                brandTitle.innerHTML = 'Join Mobility<br>Health Care Today';
                brandDesc.textContent = 'Create your account as a patient or a doctor and become part of Sri Lanka\'s most connected healthcare network.';
                brandFeatures.innerHTML = `
                    <div class="f"><div class="dot">👥</div> Growing patient community</div>
                    <div class="f"><div class="dot">👨‍⚕️</div> Verified VP & VOG doctors</div>
                    <div class="f"><div class="dot">🔒</div> Secure OTP email verification</div>
                `;
            }
        }

        // Initialize on page load based on old() value
        selectRole(document.getElementById('role').value);

        const form = document.getElementById('register-form');
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const slmcInput = document.getElementById('slmc_reg_no');
        const slmcMsg = document.getElementById('slmc-check-msg');

        function setError(el, msg) {
            el.textContent = msg;
            el.style.display = msg ? 'block' : 'none';
        }

        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            const err = document.getElementById('phone-error');
            if (this.value.length > 0 && this.value.length !== 10) {
                setError(err, 'Phone number must be exactly 10 digits.');
            } else if (this.value.length === 10 && this.value[0] !== '0') {
                setError(err, 'Phone number must start with 0.');
            } else {
                setError(err, '');
            }
        });

        nameInput.addEventListener('input', function () {
            const err = document.getElementById('name-error');
            if (this.value && !/^[A-Za-z\s]*$/.test(this.value)) {
                this.value = this.value.replace(/[^A-Za-z\s]/g, '');
                setError(err, 'Only letters and spaces are allowed.');
            } else {
                setError(err, '');
            }
        });

        emailInput.addEventListener('blur', function () {
            const err = document.getElementById('email-error');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailPattern.test(this.value)) {
                setError(err, 'Please enter a valid email address.');
            } else {
                setError(err, '');
            }
        });

        function checkPasswordMatch() {
            const err = document.getElementById('confirm-error');
            if (confirmInput.value && passwordInput.value !== confirmInput.value) {
                setError(err, 'Passwords do not match.');
            } else {
                setError(err, '');
            }
        }
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmInput.addEventListener('input', checkPasswordMatch);

        passwordInput.addEventListener('input', function () {
            const err = document.getElementById('password-error');
            if (this.value && this.value.length < 8) {
                setError(err, 'Password must be at least 8 characters.');
            } else {
                setError(err, '');
            }
        });

        // --- SLMC Live Verification ---
        let slmcVerified = false;
        let slmcTimer;

        if (slmcInput) {
            slmcInput.addEventListener('input', function () {
                slmcVerified = false;
                clearTimeout(slmcTimer);
                const value = this.value.trim();

                if (value.length < 5) {
                    slmcMsg.style.display = 'none';
                    return;
                }

                slmcMsg.style.display = 'block';
                slmcMsg.style.color = '#6b7280';
                slmcMsg.textContent = 'Checking...';

                slmcTimer = setTimeout(function () {
                    fetch("{{ route('slmc.verify') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ slmc_reg_no: value, name: nameInput.value })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.valid) {
                            slmcVerified = true;
                            slmcMsg.style.color = '#16a34a';
                            slmcMsg.textContent = '✓ ' + data.message;
                            if (data.doctor_type) {
                                document.getElementById('doctor_type').value = data.doctor_type;
                            }
                        } else {
                            slmcVerified = false;
                            slmcMsg.style.color = '#ef4444';
                            slmcMsg.textContent = '✗ ' + data.message;
                        }
                    })
                    .catch(() => {
                        slmcVerified = false;
                        slmcMsg.style.color = '#ef4444';
                        slmcMsg.textContent = '✗ Could not verify. Please try again.';
                    });
                }, 600);
            });

            nameInput.addEventListener('blur', function () {
                if (slmcInput.value.trim().length >= 5) {
                    slmcInput.dispatchEvent(new Event('input'));
                }
            });
        }

        form.addEventListener('submit', function (e) {
            let valid = true;

            if (!/^[A-Za-z\s]{3,}$/.test(nameInput.value)) {
                setError(document.getElementById('name-error'), 'Enter a valid name (letters only, min 3 characters).');
                valid = false;
            }

            if (!/^0[0-9]{9}$/.test(phoneInput.value)) {
                setError(document.getElementById('phone-error'), 'Phone number must be exactly 10 digits starting with 0.');
                valid = false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                setError(document.getElementById('email-error'), 'Please enter a valid email address.');
                valid = false;
            }

            if (passwordInput.value.length < 8) {
                setError(document.getElementById('password-error'), 'Password must be at least 8 characters.');
                valid = false;
            }

            if (passwordInput.value !== confirmInput.value) {
                setError(document.getElementById('confirm-error'), 'Passwords do not match.');
                valid = false;
            }

            const role = document.getElementById('role').value;
            if (role === 'doctor' && !slmcVerified) {
                slmcMsg.style.display = 'block';
                slmcMsg.style.color = '#ef4444';
                slmcMsg.textContent = '✗ Please enter a valid, verified SLMC registration number before submitting.';
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</x-guest-layout>
