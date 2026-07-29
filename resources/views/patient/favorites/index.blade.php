<x-app-layout>
<div style="padding:24px; max-width:900px; margin:0 auto;">
    <h1 style="font-size:20px; font-weight:700; margin-bottom:20px;">My Favorite Doctors</h1>

    <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;">
        @forelse($favorites as $fav)
            <div style="background:#fff; border-radius:12px; padding:18px; box-shadow:0 1px 3px rgba(0,0,0,0.06); display:flex; gap:14px;">
                <img src="{{ $fav->doctor->profile_photo ? asset('storage/'.$fav->doctor->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($fav->doctor->user->name) }}"
                     style="width:56px; height:56px; border-radius:50%; object-fit:cover;">
                <div style="flex:1;">
                    <p style="font-weight:600; font-size:14px; margin:0;">{{ $fav->doctor->user->name }}</p>
                    <p style="font-size:12px; color:#6b7280; margin:3px 0;">{{ $fav->doctor->doctor_type == 'VOG' ? 'VOG Specialist' : 'VP (General Physician)' }}</p>
                    <p style="font-size:12px; color:#9ca3af; margin:0 0 8px;">{{ $fav->doctor->hospital->name ?? '-' }}</p>
                    <a href="{{ route('doctors.show', $fav->doctor->id) }}" style="font-size:12px; color:#4f46e5; text-decoration:none; font-weight:600;">View Profile &rarr;</a>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1; background:#fff; border-radius:12px; padding:40px; text-align:center; color:#9ca3af; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                No favorite doctors yet. Browse doctors and tap the ❤️ icon to save them here.
            </div>
        @endforelse
    </div>
</div>
</x-app-layout>
