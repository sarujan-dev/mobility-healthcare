<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mobility Health Care System</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background:#fff; color:#111827; }

    .navbar { display:flex; justify-content:space-between; align-items:center; padding:18px 60px; border-bottom:1px solid #f1f1f4; position:sticky; top:0; background:#fff; z-index:10; }
    .navbar .logo { display:flex; align-items:center; gap:10px; font-weight:700; font-size:18px; color:#161637; text-decoration:none; }
    .navbar nav { display:flex; gap:32px; }
    .navbar nav a { text-decoration:none; color:#374151; font-size:14px; font-weight:500; }
    .navbar nav a:hover { color:#4f46e5; }
    .navbar .btn-login { background:#4f46e5; color:#fff; padding:11px 24px; border-radius:8px; text-decoration:none; font-size:14px; font-weight:600; }

    .hero { display:flex; align-items:center; gap:60px; padding:60px; }
    .hero-visual { width:320px; height:320px; background:radial-gradient(circle, #e0e7ff 0%, #f5f6ff 70%, transparent 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; }
    .hero-visual .doc-emoji { font-size:150px; }
    .floating-icon { position:absolute; width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 8px 20px rgba(0,0,0,0.1); background:#fff; }
    .fi-1 { top:10px; left:10px; background:#4f46e5; color:#fff; }
    .fi-2 { top:50px; right:0; background:#fee2e2; }
    .fi-3 { bottom:20px; left:0; background:#dcfce7; }
    .hero-text { flex:1; }
    .hero-text h1 { font-size:38px; font-weight:800; line-height:1.2; }
    .hero-text h1 .highlight { color:#4f46e5; }
    .hero-text p { margin-top:14px; font-size:15px; color:#6b7280; line-height:1.6; max-width:560px; }
    .hero-buttons { margin-top:24px; display:flex; gap:12px; }
    .btn-primary { background:#4f46e5; color:#fff; padding:14px 26px; border-radius:10px; text-decoration:none; font-weight:700; font-size:15px; box-shadow:0 4px 14px rgba(79,70,229,0.3); }
    .btn-secondary { background:#fff; color:#4f46e5; border:1.5px solid #4f46e5; padding:14px 26px; border-radius:10px; text-decoration:none; font-weight:600; font-size:15px; }

    .section-padding { padding:60px; }

    /* How It Works */
    .how-it-works { background:#f9fafb; }
    .how-it-works h2, #role-select h2 { text-align:center; font-size:26px; font-weight:800; margin-bottom:8px; }
    .section-subtitle { text-align:center; color:#6b7280; font-size:14px; margin-bottom:40px; }
    .steps { display:grid; grid-template-columns:repeat(4, 1fr); gap:20px; }
    .step-card { background:#fff; border-radius:14px; padding:26px 20px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
    .step-num { width:34px; height:34px; background:#4f46e5; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; margin:0 auto 12px; }
    .step-icon { font-size:30px; margin-bottom:8px; }
    .step-card h3 { font-size:14px; font-weight:700; margin-bottom:6px; }
    .step-card p { font-size:12.5px; color:#6b7280; line-height:1.5; }

    /* Features */
    .features { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
    .feature-card { background:#fff; border:1px solid #f1f1f4; border-radius:12px; padding:22px; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
    .feature-card .f-icon { width:42px; height:42px; border-radius:10px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:12px; }
    .feature-card h3 { font-size:15px; font-weight:600; margin-bottom:4px; }
    .feature-card p { font-size:13px; color:#6b7280; }

    /* Role Select */
    .role-cards { display:grid; grid-template-columns:1fr 1fr; gap:24px; max-width:1000px; margin:0 auto; }
    .role-card { border:2px solid #e5e7eb; border-radius:16px; padding:32px; text-align:center; transition:all 0.2s; }
    .role-card:hover { border-color:#4f46e5; box-shadow:0 8px 24px rgba(79,70,229,0.12); }
    .role-card .icon { font-size:46px; margin-bottom:14px; }
    .role-card h3 { font-size:18px; font-weight:700; margin-bottom:8px; }
    .role-card > p { font-size:13px; color:#6b7280; margin-bottom:16px; line-height:1.6; }
    .role-card ul { text-align:left; font-size:12.5px; color:#374151; list-style:none; margin-bottom:20px; padding-left:10px; }
    .role-card ul li { padding:5px 0; display:flex; align-items:center; gap:8px; }
    .role-card ul li::before { content:'✓'; color:#16a34a; font-weight:700; }
    .role-card .btn-select { background:#4f46e5; color:#fff; padding:11px 26px; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none; display:inline-block; }

    /* About */
    .about-section { background:#f9fafb; display:flex; align-items:center; gap:50px; }
    .about-visual { width:240px; height:240px; border-radius:24px; background:linear-gradient(135deg,#e0e7ff,#fdf2f8); display:flex; align-items:center; justify-content:center; font-size:90px; flex-shrink:0; }
    .about-text h2 { font-size:26px; font-weight:800; margin-bottom:14px; }
    .about-text h2 span { color:#4f46e5; }
    .about-text p { font-size:14px; color:#6b7280; line-height:1.7; margin-bottom:12px; }
    .about-stats { display:flex; gap:32px; margin-top:18px; }
    .about-stats .num { font-size:20px; font-weight:800; color:#4f46e5; }
    .about-stats .lbl { font-size:11px; color:#6b7280; }

    /* Help Banner */
    .help-section { background:#f0f4ff; }
    .help-inner { display:flex; align-items:center; gap:22px; max-width:900px; margin:0 auto; }
    .help-icon { font-size:42px; }
    .help-text h3 { font-size:16px; font-weight:700; margin-bottom:5px; }
    .help-text p { font-size:13px; color:#4b5563; }
    .help-text a { color:#4f46e5; font-weight:600; text-decoration:none; }

    /* Contact */
    .contact-section h2 { font-size:26px; font-weight:800; text-align:center; }
    .contact-section h2 span { color:#4f46e5; }
    .contact-section > p.subtitle { text-align:center; color:#6b7280; font-size:14px; margin-top:8px; margin-bottom:36px; }
    .contact-grid { display:grid; grid-template-columns:1fr 1.2fr; gap:36px; max-width:1000px; margin:0 auto; }
    .contact-info { display:flex; flex-direction:column; gap:18px; }
    .contact-info .item { display:flex; gap:14px; align-items:flex-start; }
    .contact-info .item .ic { width:42px; height:42px; border-radius:10px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:19px; flex-shrink:0; }
    .contact-info .item h4 { font-size:13px; font-weight:600; margin-bottom:2px; }
    .contact-info .item p { font-size:12.5px; color:#6b7280; }
    .contact-form { background:#fff; border:1px solid #f1f1f4; border-radius:14px; padding:26px; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
    .contact-form input, .contact-form textarea { width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:11px 14px; font-size:14px; margin-bottom:14px; font-family:inherit; }
    .contact-form textarea { resize:vertical; min-height:90px; }
    .contact-form button { background:#4f46e5; color:#fff; border:none; padding:12px 26px; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; }
    .success-msg { background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:16px; }

    footer { text-align:center; padding:24px; background:#161637; color:#c4c6e0; font-size:13px; }

    @media (max-width: 900px) {
        .navbar { padding:18px 24px; }
        .section-padding { padding:40px 24px; }
        .hero { flex-direction:column; padding:40px 24px; }
        .hero-visual { width:260px; height:260px; }
        .hero-visual .doc-emoji { font-size:120px; }
        .steps { grid-template-columns:repeat(2,1fr); }
        .features { grid-template-columns:repeat(2,1fr); }
        .role-cards { grid-template-columns:1fr; }
        .about-section { flex-direction:column; padding:40px 24px; }
        .contact-grid { grid-template-columns:1fr; }
    }
</style>
</head>
<body>

<div class="navbar">
    <a href="{{ route('welcome') }}" class="logo">🩺 Mobility Health Care</a>
    <nav>
        <a href="{{ route('welcome') }}">Home</a>
        <a href="#how-it-works">How It Works</a>
        <a href="#about">About Us</a>
        <a href="#contact">Contact Us</a>
    </nav>
    <a href="{{ route('login') }}" class="btn-login">Login / Register</a>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-visual">
        <div class="doc-emoji">👩‍⚕️</div>
        <div class="floating-icon fi-1">📅</div>
        <div class="floating-icon fi-2">❤️</div>
        <div class="floating-icon fi-3">➕</div>
    </div>
    <div class="hero-text">
        <h1>Find Available Doctors <span class="highlight">Anytime, Anywhere</span></h1>
        <p>Real-time doctor availability and instant booking for VP and VOG specialists across Sri Lanka. Simple, guided, and built for everyone — no tech experience needed.</p>
        <div class="hero-buttons">
            <a href="{{ route('register') }}" class="btn-primary">Get Started — It's Free</a>
            <a href="#how-it-works" class="btn-secondary">▶ See How It Works</a>
        </div>
    </div>
</div>

<!-- HOW IT WORKS -->
<div class="how-it-works section-padding" id="how-it-works">
    <h2>How It Works</h2>
    <p class="section-subtitle">Just 3 simple steps to get the care you need</p>
    <div class="steps">
        <div class="step-card">
            <div class="step-num">1</div>
            <div class="step-icon">📝</div>
            <h3>Create Your Account</h3>
            <p>Sign up as a Patient in under 2 minutes with your name, phone, and email.</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <div class="step-icon">🔍</div>
            <h3>Find a Doctor</h3>
            <p>Search by doctor type (VP or VOG) and see who's available right now.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <div class="step-icon">📅</div>
            <h3>Book Instantly</h3>
            <p>Pick an available date and confirm your appointment in a few clicks.</p>
        </div>
        <div class="step-card">
            <div class="step-num">4</div>
            <div class="step-icon">✅</div>
            <h3>Get Confirmed</h3>
            <p>Receive an email with your QR code once the doctor confirms your visit.</p>
        </div>
    </div>
</div>

<!-- FEATURES -->
<div class="section-padding">
    <div class="features">
        <div class="feature-card">
            <div class="f-icon">🟢</div>
            <h3>Real-time Availability</h3>
            <p>Check live status of doctors before you go</p>
        </div>
        <div class="feature-card">
            <div class="f-icon">📅</div>
            <h3>Instant Booking</h3>
            <p>Book your slot in seconds, no phone calls</p>
        </div>
        <div class="feature-card">
            <div class="f-icon">🚨</div>
            <h3>Emergency Support</h3>
            <p>Find nearest available VOG doctor immediately</p>
        </div>
        <div class="feature-card">
            <div class="f-icon">📧</div>
            <h3>Email Notifications</h3>
            <p>Get booking confirmations and reminders</p>
        </div>
    </div>
</div>


<!-- ROLE SELECT -->
<div class="section-padding" id="role-select">
    <h2>Are You a Patient or a Doctor?</h2>
    <p class="section-subtitle">Choose the option that fits you — registration is different for each</p>
    <div class="role-cards">
        <div class="role-card">
            <div class="icon">🧑‍🦱</div>
            <h3>I'm a Patient</h3>
            <p>Looking to find and book an appointment with a doctor.</p>
            <ul>
                <li>Search available doctors instantly</li>
                <li>Book appointments in a few clicks</li>
                <li>Use Emergency Finder if urgent</li>
                <li>Chat with our AI health assistant</li>
            </ul>
            <a href="{{ route('register') }}" class="btn-select">Register as Patient</a>
        </div>
        <div class="role-card">
            <div class="icon">👨‍⚕️</div>
            <h3>I'm a Doctor</h3>
            <p>Looking to join and manage your appointments online.</p>
            <ul>
                <li>Requires a valid SLMC registration number</li>
                <li>Set your weekly working hours</li>
                <li>Manage patient appointments easily</li>
                <li>Approved by admin before you can log in</li>
            </ul>
            <a href="{{ route('register') }}" class="btn-select">Register as Doctor</a>
        </div>
    </div>
</div>

<!-- ABOUT US -->
<div class="about-section section-padding" id="about">
    <div class="about-visual">🏥</div>
    <div class="about-text">
        <h2>About <span>Mobility Health Care</span></h2>
        <p>Mobility Health Care System connects patients across Sri Lanka with verified VP (General Physician) and VOG (Obstetrician & Gynaecologist) doctors in real time. No more calling multiple hospitals or waiting in the dark about availability.</p>
        <p>Our mission is simple: reduce waiting time, improve access to healthcare, and provide instant support for emergencies — especially for pregnant mothers who need urgent VOG care.</p>
        <div class="about-stats">
            <div><div class="num">{{ $totalDoctors }}+</div><div class="lbl">Verified Doctors</div></div>
            <div><div class="num">{{ $totalPatients }}+</div><div class="lbl">Registered Patients</div></div>
            <div><div class="num">24/7</div><div class="lbl">Emergency Support</div></div>
        </div>
    </div>
</div>

<!-- HELP SECTION -->
<div class="help-section section-padding">
    <div class="help-inner">
        <div class="help-icon">💬</div>
        <div class="help-text">
            <h3>New here and not sure where to start?</h3>
            <p>Contact our support team — we're happy to walk you through it. <a href="#contact">Contact Us →</a></p>
        </div>
    </div>
</div>

<!-- CONTACT US -->
<div class="contact-section section-padding" id="contact">
    <h2>Get in <span>Touch</span></h2>
    <p class="subtitle">Have a question or need help? We'd love to hear from you.</p>
    <div class="contact-grid">
        <div class="contact-info">
            <div class="item">
                <div class="ic">📍</div>
                <div><h4>Our Location</h4><p>Colombo, Sri Lanka</p></div>
            </div>
            <div class="item">
                <div class="ic">📞</div>
                <div><h4>Phone</h4><p>+94 77 123 4567</p></div>
            </div>
            <div class="item">
                <div class="ic">✉️</div>
                <div><h4>Email</h4><p>support@mobilityhealthcare.lk</p></div>
            </div>
            <div class="item">
                <div class="ic">🕒</div>
                <div><h4>Support Hours</h4><p>24/7 for emergencies, 8AM–8PM for general queries</p></div>
            </div>
        </div>
        <div class="contact-form">
            @if(session('success'))
                <div class="success-msg">✓ {{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('contact.submit') }}">
                @csrf
                <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                <textarea name="message" placeholder="Your Message" required>{{ old('message') }}</textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</div>

<footer>© {{ date('Y') }} Mobility Health Care System. All rights reserved.</footer>

</body>
</html>
