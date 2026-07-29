<x-guest-layout>
    <x-slot name="brand">
        <h1>Join Mobility<br>Health Care Today</h1>
        <p>Create your account as a patient or a doctor and become part of Sri Lanka's most connected healthcare network.</p>
        <div class="brand-features">
            <div class="f"><div class="dot">👥</div> Growing patient community</div>
            <div class="f"><div class="dot">👨‍⚕️</div> Verified VP & VOG doctors</div>
            <div class="f"><div class="dot">🔒</div> Secure OTP email verification</div>
        </div>
    </x-slot>

    <h2 style="font-size:24px; font-weight:800; text-align:center; color:#111827; margin:0;">Create Your Account</h2>
    <p style="text-align:center; color:#6b7280; font-size:14px; margin-top:6px; margin-bottom:32px;">Join Mobility Health Care System</p>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="register-form" novalidate>
        @csrf

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
                <p class="field-error" id="name-error"></p>
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
                <p class="field-error" id="phone-error"></p>
            </div>
        </div>

        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <span class="icon">✉️</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username" placeholder="you@example.com">
            </div>
            <p class="field-error" id="email-error"></p>
        </div>

        <div class="field no-icon">
            <label for="role">Register As</label>
            <select id="role" name="role" required onchange="toggleDoctorFields(this.value)">
                <option value="">-- Select Role --</option>
                <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor (VP/VOG)</option>
            </select>
        </div>

        <div class="doctor-fields" id="doctor-fields" style="display: {{ old('role') == 'doctor' ? 'block' : 'none' }};">
            <div class="row-2">
                <div class="field no-icon">
                    <label for="doctor_type">Doctor Type</label>
                    <select id="doctor_type" name="doctor_type">
                        <option value="VP" {{ old('doctor_type')=='VP'?'selected':'' }}>VP (General Physician)</option>
                        <option value="VOG" {{ old('doctor_type')=='VOG'?'selected':'' }}>VOG Specialist</option>
                    </select>
                </div>
                <div class="field no-icon">
                    <label for="slmc_reg_no">SLMC Reg. No.</label>
                    <input id="slmc_reg_no" type="text" name="slmc_reg_no" value="{{ old('slmc_reg_no') }}"
                        pattern="SLMC-[0-9]{3,10}" title="Format: SLMC-12345" placeholder="SLMC-12345">
                    <p class="field-error" id="slmc-error"></p>
                </div>
            </div>
            <div class="field no-icon">
                <label for="license_document">Medical License / Certificate</label>
                <input id="license_document" type="file" name="license_document" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-hint">PDF or image, max 4MB. Verified by admin before activation.</div>
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
                <p class="field-error" id="password-error"></p>
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrap">
                    <span class="icon">🔒</span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                        autocomplete="new-password" placeholder="••••••••">
                </div>
                <p class="field-error" id="confirm-error"></p>
            </div>
        </div>

        <button type="submit" class="btn-submit">Create Account</button>

        <p class="switch-text">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
    </form>

    <script>
        function toggleDoctorFields(role) {
            document.getElementById('doctor-fields').style.display = (role === 'doctor') ? 'block' : 'none';
        }

        const form = document.getElementById('register-form');
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const slmcInput = document.getElementById('slmc_reg_no');

        function setError(el, msg) {
            el.textContent = msg;
            el.style.display = msg ? 'block' : 'none';
        }

        // Phone: only allow digits while typing, max 10
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

        // Name: only letters and spaces
        nameInput.addEventListener('input', function () {
            const err = document.getElementById('name-error');
            if (this.value && !/^[A-Za-z\s]*$/.test(this.value)) {
                this.value = this.value.replace(/[^A-Za-z\s]/g, '');
                setError(err, 'Only letters and spaces are allowed.');
            } else {
                setError(err, '');
            }
        });

        // Email: basic format check
        emailInput.addEventListener('blur', function () {
            const err = document.getElementById('email-error');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailPattern.test(this.value)) {
                setError(err, 'Please enter a valid email address.');
            } else {
                setError(err, '');
            }
        });

        // SLMC number format
        if (slmcInput) {
            slmcInput.addEventListener('blur', function () {
                const err = document.getElementById('slmc-error');
                if (this.value && !/^SLMC-[0-9]{3,10}$/i.test(this.value)) {
                    setError(err, 'Format must be like SLMC-12345.');
                } else {
                    setError(err, '');
                }
            });
        }

        // Password match check
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

        // Password strength check
        passwordInput.addEventListener('input', function () {
            const err = document.getElementById('password-error');
            if (this.value && this.value.length < 8) {
                setError(err, 'Password must be at least 8 characters.');
            } else {
                setError(err, '');
            }
        });

        // Final submit-time validation
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
            if (role === 'doctor' && slmcInput.value && !/^SLMC-[0-9]{3,10}$/i.test(slmcInput.value)) {
                setError(document.getElementById('slmc-error'), 'Format must be like SLMC-12345.');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</x-guest-layout>
