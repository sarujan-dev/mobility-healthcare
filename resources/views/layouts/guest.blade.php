<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mobility Health Care') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing:border-box; }
        body { font-family: 'figtree', 'Segoe UI', Arial, sans-serif; margin:0; background:#f4f5fb; }

        .auth-wrapper { display:flex; min-height:100vh; }

        .brand-panel { flex:1; background:linear-gradient(160deg, #161637 0%, #312e81 50%, #4f46e5 100%); color:#fff; display:none; flex-direction:column; justify-content:center; padding:70px; position:relative; overflow:hidden; }
        .brand-panel::before { content:''; position:absolute; width:400px; height:400px; border-radius:50%; background:rgba(255,255,255,0.05); top:-100px; right:-100px; }
        .brand-panel::after { content:''; position:absolute; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.04); bottom:-80px; left:-80px; }
        .brand-logo { font-size:38px; margin-bottom:20px; position:relative; z-index:1; }
        .brand-panel h1 { font-size:30px; font-weight:800; line-height:1.3; margin:0 0 14px; position:relative; z-index:1; }
        .brand-panel p { font-size:15px; color:#c7c9f0; line-height:1.7; max-width:380px; position:relative; z-index:1; margin:0; }
        .brand-features { margin-top:36px; display:flex; flex-direction:column; gap:16px; position:relative; z-index:1; }
        .brand-features .f { display:flex; align-items:center; gap:12px; font-size:14px; color:#e0e1f9; }
        .brand-features .f .dot { width:34px; height:34px; border-radius:10px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
        .brand-help { margin-top:40px; background:rgba(255,255,255,0.08); border-radius:12px; padding:18px 20px; position:relative; z-index:1; }
        .brand-help p { font-size:13px; color:#e0e1f9; margin:0; line-height:1.6; }
        .brand-help p strong { color:#fff; }

        .form-panel { flex:1; display:flex; align-items:center; justify-content:center; padding:40px; }
        .form-card { width:100%; max-width:440px; }
        .form-card .top-logo { text-align:center; margin-bottom:8px; font-size:26px; }

        .field { margin-bottom:18px; }
        .field label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:7px; }
        .field .helper-text { font-size:11.5px; color:#9ca3af; margin-top:5px; }
        .field .input-wrap { position:relative; }
        .field .input-wrap .icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:15px; opacity:0.5; pointer-events:none; }
        .field input[type="text"], .field input[type="email"], .field input[type="password"], .field input[type="date"], .field select, .field textarea {
            width:100%; border:1.5px solid #e5e7eb; border-radius:10px; padding:12px 14px 12px 40px; font-size:14px; transition:border-color 0.2s; background:#fafafa;
        }
        .field input:focus, .field select:focus, .field textarea:focus { outline:none; border-color:#4f46e5; background:#fff; }
        .field.no-icon input, .field.no-icon select { padding-left:14px; }
        .field input[type="file"] { width:100%; border:1.5px solid #e5e7eb; border-radius:10px; padding:10px; font-size:13px; background:#fafafa; }

        .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

        .options-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; font-size:13px; }
        .options-row label { display:flex; align-items:center; gap:6px; color:#6b7280; cursor:pointer; }
        .options-row a { color:#4f46e5; text-decoration:none; font-weight:600; }

        .btn-submit { width:100%; background:linear-gradient(135deg, #4f46e5, #6366f1); color:#fff; border:none; padding:14px; border-radius:10px; font-weight:700; font-size:15px; cursor:pointer; box-shadow:0 4px 14px rgba(79,70,229,0.35); }
        .btn-submit:hover { opacity:0.95; }

        .switch-text { text-align:center; font-size:13px; color:#6b7280; margin-top:24px; }
        .switch-text a { color:#4f46e5; font-weight:700; text-decoration:none; }

        .doctor-fields { border:1.5px dashed #d1d5db; border-radius:12px; padding:16px; margin-bottom:18px; background:#fafaff; }
        .doctor-fields .field { margin-bottom:14px; }
        .doctor-fields .field:last-child { margin-bottom:0; }
        .file-hint { font-size:11px; color:#9ca3af; margin-top:4px; }

        .error-box { background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:10px; margin-bottom:18px; font-size:13px; }
        .error-box ul { margin:0; padding-left:18px; }
        .status-box { background:#dcfce7; color:#166534; padding:12px 16px; border-radius:10px; margin-bottom:18px; font-size:13px; text-align:center; }

        .role-toggle { display:flex; gap:10px; margin-bottom:22px; }
        .role-toggle-btn { flex:1; padding:14px 10px; border-radius:10px; border:2px solid #e5e7eb; background:#fff; text-align:center; cursor:pointer; transition:all 0.15s; }
        .role-toggle-btn.active { border-color:#4f46e5; background:#eef2ff; }
        .role-toggle-btn .icon { font-size:22px; display:block; margin-bottom:4px; }
        .role-toggle-btn .label { font-size:13px; font-weight:600; color:#374151; }

        .step-indicator { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:24px; }
        .step-dot { width:8px; height:8px; border-radius:50%; background:#e5e7eb; }
        .step-dot.active { background:#4f46e5; width:20px; border-radius:4px; }

        @media (min-width: 900px) {
            .brand-panel { display:flex !important; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="brand-panel">
        <div class="brand-logo">🩺</div>
        {{ $brand ?? '' }}
    </div>
    <div class="form-panel">
        <div class="form-card">
            <div class="top-logo">🩺</div>
            {{ $slot }}
        </div>
    </div>
</div>

</body>
</html>
