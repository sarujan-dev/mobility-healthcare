<x-app-layout>
<div style="padding:24px;">
    <h1 style="font-size:22px; font-weight:700; margin-bottom:20px;">Contact Messages</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">{{ session('success') }}</div>
    @endif

    <div style="display:flex; flex-direction:column; gap:14px;">
        @forelse($messages as $msg)
            <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-weight:600; font-size:15px; margin:0;">{{ $msg->name }}</p>
                        <p style="font-size:13px; color:#4f46e5; margin:2px 0 0;">{{ $msg->email }}</p>
                    </div>
                    <span style="font-size:12px; color:#9ca3af;">{{ $msg->created_at->diffForHumans() }}</span>
                </div>
                <p style="font-size:14px; color:#374151; margin-top:12px; line-height:1.5;">{{ $msg->message }}</p>
                <form method="POST" action="{{ route('admin.contact-messages.destroy', $msg->id) }}" style="margin-top:12px;" onsubmit="return confirm('Delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#fee2e2; color:#b91c1c; border:none; padding:6px 14px; border-radius:6px; font-size:12px; cursor:pointer;">Delete</button>
                </form>
            </div>
        @empty
            <div style="background:#fff; border-radius:10px; padding:40px; text-align:center; color:#9ca3af;">No messages yet.</div>
        @endforelse
    </div>

    <div style="margin-top:16px;">{{ $messages->links() }}</div>
</div>
</x-app-layout>
