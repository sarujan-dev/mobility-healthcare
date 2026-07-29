<x-app-layout>
<div style="padding:24px; max-width:1100px; margin:0 auto;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">SLMC Registry Management</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">⚠️ {{ session('error') }}</div>
    @endif

    <!-- Add New SLMC Entry -->
    <div style="background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:16px; font-weight:600; margin:0 0 16px;">Add New SLMC Registration</h2>

        @if($errors->any())
            <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.slmc.store') }}" style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:12px; align-items:end;">
            @csrf
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px;">SLMC Reg. No.</label>
                <input type="text" name="slmc_reg_no" placeholder="SLMC-12345" value="{{ old('slmc_reg_no') }}"
                    style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:13px; box-sizing:border-box;" required>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px;">Doctor Name</label>
                <input type="text" name="registered_name" placeholder="Full name" value="{{ old('registered_name') }}"
                    style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:13px; box-sizing:border-box;" required>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px;">Doctor Type</label>
                <select name="doctor_type" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:13px; box-sizing:border-box;" required>
                    <option value="">-- Select --</option>
                    <option value="VP" {{ old('doctor_type')=='VP'?'selected':'' }}>VP (General Physician)</option>
                    <option value="VOG" {{ old('doctor_type')=='VOG'?'selected':'' }}>VOG Specialist</option>
                </select>
            </div>
            <button type="submit" style="background:#4f46e5; color:#fff; padding:10px 24px; border-radius:8px; border:none; font-weight:600; font-size:13px; cursor:pointer; white-space:nowrap;">
                + Add Entry
            </button>
        </form>
    </div>

    <!-- Search -->
    <div style="margin-bottom:16px;">
        <form method="GET" action="{{ route('admin.slmc.index') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by SLMC number or name..."
                style="width:100%; max-width:400px; border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; font-size:13px;"
                onchange="this.form.submit()">
        </form>
    </div>

    <!-- Registry Table -->
    <div style="background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06); overflow:hidden;">
        <table style="width:100%; text-align:left; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb; border-bottom:2px solid #f1f1f4;">
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280;">SLMC No.</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280;">Registered Name</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280;">Type</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280;">Status</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280;">Added On</th>
                    <th style="padding:14px 16px; font-size:12px; font-weight:600; color:#6b7280;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                    <tr style="border-bottom:1px solid #f1f1f4;">
                        <td style="padding:14px 16px; font-weight:600; font-size:13px;">{{ $reg->slmc_reg_no }}</td>
                        <td style="padding:14px 16px; font-size:13px;">{{ $reg->registered_name }}</td>
                        <td style="padding:14px 16px; font-size:13px;">{{ $reg->doctor_type }}</td>
                        <td style="padding:14px 16px;">
                            <span style="font-size:12px; padding:4px 10px; border-radius:20px; font-weight:600;
                                {{ $reg->is_used ? 'background:#dbeafe; color:#1e40af;' : 'background:#dcfce7; color:#166534;' }}">
                                {{ $reg->is_used ? 'Registered' : 'Available' }}
                            </span>
                        </td>
                        <td style="padding:14px 16px; font-size:12px; color:#6b7280;">{{ $reg->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 16px;">
                            @if(!$reg->is_used)
                                <form method="POST" action="{{ route('admin.slmc.destroy', $reg->id) }}" onsubmit="return confirm('Remove this SLMC entry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:#fee2e2; color:#b91c1c; border:none; padding:6px 14px; border-radius:6px; font-size:12px; cursor:pointer;">Delete</button>
                                </form>
                            @else
                                <span style="font-size:12px; color:#9ca3af;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:40px; text-align:center; color:#9ca3af;">No SLMC entries yet. Add one above.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $registrations->links() }}</div>
</div>
</x-app-layout>
