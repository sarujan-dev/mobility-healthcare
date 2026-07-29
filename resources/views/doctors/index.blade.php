<x-app-layout>
<div style="padding:24px;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">Find Doctors (VP / VOG)</h1>

    <div style="display:grid; grid-template-columns:280px 1fr; gap:24px;">

        <!-- Filters Sidebar -->
        <div>
            <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h2 style="font-size:15px; font-weight:600; margin:0;">Filters</h2>
                    <a href="{{ route('doctors.index') }}" style="font-size:12px; color:#4f46e5; text-decoration:none;">Clear All</a>
                </div>

                <form method="GET" action="{{ route('doctors.index') }}" id="filter-form">
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px;">Search by Name</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Doctor name..."
                            style="width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:9px 12px; font-size:13px; box-sizing:border-box;"
                            oninput="document.getElementById('filter-form').submit()">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px;">Doctor Type</label>
                        <select name="doctor_type" onchange="this.form.submit()"
                            style="width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:9px 12px; font-size:13px; box-sizing:border-box;">
                            <option value="">All (VP & VOG)</option>
                            <option value="VP" {{ request('doctor_type')=='VP'?'selected':'' }}>VP (General Physician)</option>
                            <option value="VOG" {{ request('doctor_type')=='VOG'?'selected':'' }}>VOG Specialist</option>
                        </select>
                    </div>


                </form>
            </div>
        </div>

       



            <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;">
                @forelse($doctors as $doctor)
                    <div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06); display:flex; flex-direction:column;">
                        <div style="display:flex; gap:14px; margin-bottom:14px;">
                            <img src="{{ $doctor->profile_photo ? asset('storage/'.$doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=e0e7ff&color=4f46e5' }}"
                                 style="width:60px; height:60px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                            <div style="flex:1; min-width:0;">
                                <p style="font-weight:700; font-size:15px; margin:0 0 4px; color:#111827;">{{ $doctor->user->name }}</p>
                                <span style="display:inline-block; font-size:11px; padding:3px 10px; border-radius:10px; white-space:nowrap;
                                    {{ $doctor->availability_status=='Available' ? 'background:#dcfce7; color:#166534;' : 'background:#fee2e2; color:#b91c1c;' }}">
                                    {{ $doctor->availability_status }}
                                </span>
                            </div>
                        </div>

                        <p style="font-size:12px; color:#6b7280; margin:0 0 14px;">{{ $doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>

                        <a href="{{ route('doctors.show', $doctor->id) }}" style="margin-top:auto; display:block; text-align:center; background:#4f46e5; color:#fff; font-size:13px; padding:9px 14px; border-radius:8px; text-decoration:none; font-weight:600;">
                            View Profile
                        </a>
                    </div>
                @empty
                    <div style="grid-column:1/-1; background:#fff; border-radius:12px; padding:40px; text-align:center; color:#9ca3af; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                        No doctors found. Try adjusting your filters.
                    </div>
                @endforelse
            </div>

            <div style="margin-top:20px;">{{ $doctors->links() }}</div>
        </div>
    </div>
</div>
</x-app-layout>
