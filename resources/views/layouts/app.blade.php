<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mobility Health Care') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    .mobile-toggle { display:none; }
    @media (max-width: 900px) {
        .app-sidebar { position:fixed; left:-260px; top:0; height:100vh; z-index:100; transition:left 0.3s; }
        .app-sidebar.open { left:0; }
        .app-main { margin-left:0 !important; }
        .mobile-toggle { display:flex; align-items:center; justify-content:center; width:40px; height:40px; background:#4f46e5; color:#fff; border-radius:8px; border:none; font-size:20px; cursor:pointer; }
        .mobile-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99; }
        .mobile-overlay.show { display:block; }
    }

    /* Mobile responsiveness */
.mobile-menu-btn { display:none; }

@media (max-width: 900px) {
    .mobile-menu-btn {
        display:flex; align-items:center; justify-content:center;
        width:40px; height:40px; background:#fff; border:1px solid #e5e7eb;
        border-radius:8px; cursor:pointer; font-size:20px;
    }
    aside {
        position:fixed !important; left:-260px; top:0; bottom:0; z-index:100;
        transition:left 0.25s ease; width:250px !important;
    }
    aside.sidebar-open { left:0 !important; }
    .sidebar-overlay {
        display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:99;
    }
    .sidebar-overlay.active { display:block; }
}
</style>
</head>
<body class="font-sans antialiased" style="background:#f4f5f9;">

<div style="display:flex; min-height:100vh;">

    <!-- SIDEBAR -->
    <aside style="width:250px; background:#161637; color:#c4c6e0; flex-shrink:0; display:flex; flex-direction:column;">
        <div style="padding:22px 20px; display:flex; align-items:center; gap:10px; border-bottom:1px solid rgba(255,255,255,0.08);">
            <span style="font-size:22px;">🩺</span>
            <div>
                <div style="color:#fff; font-weight:700; font-size:16px; line-height:1.1;">Mobility</div>
                <div style="font-size:11px; color:#8b8dc0; line-height:1.2;">Health Care System</div>
            </div>
        </div>

        <nav style="flex:1; padding:14px 12px; display:flex; flex-direction:column; gap:2px;">
            @php
                $role = auth()->user()->role;
                $navItem = function($routeName, $icon, $label, $active) {
                    $bg = $active ? 'background:#4f46e5; color:#fff;' : 'color:#c4c6e0;';
                    return '<a href="'.route($routeName).'" style="display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:8px; text-decoration:none; font-size:14px; '.$bg.'" onmouseover="if(!this.style.background.includes(\'79, 70, 229\')) this.style.background=\'rgba(255,255,255,0.06)\'" onmouseout="if(!this.classList.contains(\'active-nav\')) this.style.background=\''.($active ? '#4f46e5' : 'transparent').'\'">'
                        .'<span style="font-size:16px; width:20px; text-align:center;">'.$icon.'</span><span>'.$label.'</span></a>';
                };
            @endphp

            @if($role === 'admin')
                {!! $navItem('admin.dashboard', '🏠', 'Dashboard', request()->routeIs('admin.dashboard')) !!}
                {!! $navItem('admin.doctors.index', '👨‍⚕️', 'Doctors', request()->routeIs('admin.doctors.*')) !!}
                {!! $navItem('admin.emergency.index', '🚨', 'Emergency VOG', request()->routeIs('admin.emergency.*')) !!}
                {!! $navItem('admin.reports.index', '📊', 'Reports', request()->routeIs('admin.reports.*')) !!}
                {!! $navItem('admin.contact-messages.index', '✉️', 'Contact Messages', request()->routeIs('admin.contact-messages.*')) !!}
                {!! $navItem('admin.slmc.index', '🪪', 'SLMC Registry', request()->routeIs('admin.slmc.*')) !!}
                @elseif($role === 'doctor')
                {!! $navItem('doctor.dashboard', '🏠', 'Dashboard', request()->routeIs('doctor.dashboard')) !!}
                {!! $navItem('doctor.profile.edit', '👤', 'My Profile', request()->routeIs('doctor.profile.*')) !!}
                {!! $navItem('doctor.schedule.index', '📅', 'Weekly Schedule', request()->routeIs('doctor.schedule.*')) !!}
                {!! $navItem('doctor.appointments.index', '📋', 'Appointments', request()->routeIs('doctor.appointments.*')) !!}
            @elseif($role === 'patient')
                {!! $navItem('patient.dashboard', '🏠', 'Dashboard', request()->routeIs('patient.dashboard')) !!}
                {!! $navItem('doctors.index', '🔍', 'Find Doctors', request()->routeIs('doctors.*')) !!}
                {!! $navItem('patient.appointments.index', '📅', 'Appointments', request()->routeIs('patient.appointments.*')) !!}
                {!! $navItem('patient.emergency.index', '🚨', 'Emergency VOG', request()->routeIs('patient.emergency.*')) !!}
                {!! $navItem('patient.chatbot.index', '🤖', 'AI Assistant', request()->routeIs('patient.chatbot.*')) !!}
                {!! $navItem('patient.history.index', '📋', 'Medical History', request()->routeIs('patient.history.*')) !!}
                {!! $navItem('patient.favorites.index', '❤️', 'Favorite Doctors', request()->routeIs('patient.favorites.*')) !!}
                {!! $navItem('patient.profile.edit', '👤', 'My Profile', request()->routeIs('patient.profile.*')) !!}
                @endif

            {!! $navItem('profile.edit', '⚙️', 'Settings', request()->routeIs('profile.edit')) !!}
        </nav>

        <div style="padding:14px 12px; border-top:1px solid rgba(255,255,255,0.08);">
            <div style="display:flex; align-items:center; gap:10px; padding:8px 10px;">
                <div style="width:34px; height:34px; border-radius:50%; background:#4f46e5; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:600; font-size:13px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="color:#fff; font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                    <div style="font-size:11px; color:#8b8dc0; text-transform:capitalize;">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" style="width:100%; text-align:left; color:#c4c6e0; background:none; border:none; padding:10px 14px; border-radius:8px; font-size:14px; cursor:pointer; display:flex; align-items:center; gap:12px;">
                    <span style="font-size:16px; width:20px; text-align:center;">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>
<div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
    <!-- MAIN CONTENT -->
    <div style="flex:1; display:flex; flex-direction:column; min-width:0;">

        <!-- TOPBAR -->
<header style="background:#fff; border-bottom:1px solid #e5e7eb; padding:14px 28px; display:flex; justify-content:space-between; align-items:center; gap:14px;">
    <div style="display:flex; align-items:center; gap:14px;">
        <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
        <h1 style="font-size:18px; font-weight:600; color:#111827; margin:0;">
            @isset($header){{ $header }}@else Dashboard @endisset
        </h1>
    </div>
<div style="display:flex; align-items:center; gap:18px;">
    <div style="display:flex; align-items:center; gap:8px;">
        <div style="width:32px; height:32px; border-radius:50%; background:#4f46e5; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:600; font-size:12px;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <span style="font-size:14px; color:#374151; font-weight:500;">{{ Str::limit(auth()->user()->name, 15) }}</span>
    </div>
</div>
        </header>

        <!-- PAGE CONTENT -->
        <main style="flex:1; overflow-y:auto;">
            {{ $slot }}
        </main>
    </div>
</div>
<script>
    function toggleSidebar() {
        document.querySelector('aside').classList.toggle('sidebar-open');
        document.getElementById('sidebar-overlay').classList.toggle('active');
    }
</script>
</body>
</html>
