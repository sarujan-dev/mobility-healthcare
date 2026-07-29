<x-app-layout>
    <div style="padding:24px; max-width:700px; margin:0 auto;">
        <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">Account Settings</h1>

        <!-- Profile Information -->
        <div style="background:#fff; border-radius:12px; padding:28px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
            <h2 style="font-size:16px; font-weight:600; margin:0 0 4px;">Profile Information</h2>
            <p style="font-size:13px; color:#6b7280; margin:0 0 20px;">Update your account's name and email address.</p>

            @if (session('status') === 'profile-updated')
                <div style="background:#dcfce7; color:#166534; padding:10px 14px; border-radius:8px; margin-bottom:16px; font-size:13px;">✓ Saved successfully.</div>
            @endif

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                    @error('name') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                    @error('email') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <button type="submit" style="background:#4f46e5; color:#fff; padding:10px 24px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Update Password -->
        <div style="background:#fff; border-radius:12px; padding:28px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
            <h2 style="font-size:16px; font-weight:600; margin:0 0 4px;">Update Password</h2>
            <p style="font-size:13px; color:#6b7280; margin:0 0 20px;">Ensure your account is using a long, random password to stay secure.</p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Current Password</label>
                    <input type="password" name="current_password"
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                    @error('current_password', 'updatePassword') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">New Password</label>
                    <input type="password" name="password"
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                    @error('password', 'updatePassword') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px;">
                </div>

                <button type="submit" style="background:#4f46e5; color:#fff; padding:10px 24px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                    Update Password
                </button>
            </form>
        </div>

        <!-- Delete Account -->
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:28px;">
            <h2 style="font-size:16px; font-weight:600; margin:0 0 4px; color:#b91c1c;">Delete Account</h2>
            <p style="font-size:13px; color:#7f1d1d; margin:0 0 20px;">Once your account is deleted, all data will be permanently removed. This cannot be undone.</p>

            <button type="button" onclick="document.getElementById('delete-modal').style.display='flex'"
                style="background:#dc2626; color:#fff; padding:10px 24px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                Delete Account
            </button>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:50;">
            <div style="background:#fff; border-radius:12px; padding:28px; max-width:420px; width:90%;">
                <h3 style="font-size:16px; font-weight:600; margin:0 0 8px;">Are you sure you want to delete your account?</h3>
                <p style="font-size:13px; color:#6b7280; margin:0 0 20px;">Please enter your password to confirm you would like to permanently delete your account.</p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <input type="password" name="password" placeholder="Password"
                        style="display:block; width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:14px; margin-bottom:16px;">
                    @error('password', 'userDeletion') <p style="color:#ef4444; font-size:12px; margin-bottom:12px;">{{ $message }}</p> @enderror

                    <div style="display:flex; gap:10px; justify-content:flex-end;">
                        <button type="button" onclick="document.getElementById('delete-modal').style.display='none'"
                            style="background:#f3f4f6; color:#374151; padding:10px 20px; border-radius:8px; border:none; font-weight:500; font-size:14px; cursor:pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="background:#dc2626; color:#fff; padding:10px 20px; border-radius:8px; border:none; font-weight:600; font-size:14px; cursor:pointer;">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
